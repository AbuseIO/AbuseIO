<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use AbuseIO\Jobs\AlertAdmin;
use AbuseIO\Jobs\QueueTest;
use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\FailedJob;
use AbuseIO\Models\Job;
use AbuseIO\Models\Ticket;
use Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Storage;
use Validator;

/**
 * Class RunCommand.
 */
class RunCommand extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'housekeeper:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run housekeeping processes';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        Log::debug(
            get_class($this).': KNOCK KNOCK! Housekeeping'
        );

        //Check all queue's their status
        $this->checkQueues();

        // Walk through all tickets to see which need closing
        if (config('main.housekeeping.tickets_close_after') !== false) {
            $this->ticketsClosing();
        }

        // Walk trough all closed tickets to see which need pruning
        if (config('main.housekeeping.closed_tickets_remove_after') !== false) {
            $this->ticketsPruning();
        }

        // Walk through mailarchive to see which need pruning
        if (config('main.housekeeping.mailarchive_remove_after') !== false) {
            $this->mailarchivePruning();
        }

        // Walk through mailarchive to see which files are orphaned
        if (config('main.housekeeping.mailarchive_remove_orphaned') !== false) {
            $this->removeUnlinkedEvidence();
        }

        Log::info(
            get_class($this).': Housekeeping has completed its run'
        );

        return true;
    }

    /**
     * Walk through all files in the mailarchive folder and remove them from the system.
     *
     * @return bool
     */
    private function removeUnlinkedEvidence()
    {
        Log::debug(
            get_class($this).': Housekeeper is starting to remove orphaned mailarchive items'
        );

        $path = '/mailarchive/';
        $startTime = time() - 3600;

        $directories = Storage::directories($path);

        // For each dated directory in the mail archive
        foreach ($directories as $directory) {
            // Get a list of all the files
            $files = Storage::files($directory);

            // then check for each file check if its linked to a database entry
            foreach ($files as $file) {
                // Check filesystem if its actually old and not just created
                if (Storage::lastModified($file) > $startTime) {
                    continue;
                }

                // Check if there might be a pending job for the file
                $basenameFile = basename($file);
                if (Job::where('payload', 'like', "%{$basenameFile}%")->count() !== 0) {
                    continue;
                }

                // Check if there might be a failed job for the file
                $basenameFile = basename($file);
                if (FailedJob::where('payload', 'like', "%{$basenameFile}%")->count() !== 0) {
                    continue;
                }

                // Check the database if it exists
                if (Evidence::where('filename', '=', $file)->count() === 0) {
                    Log::warning(
                        get_class($this).": removing orphaned mailarchive item {$file}"
                    );

                    try {
                        Storage::delete($file);
                    } catch (\Exception $e) {
                        Log::error(
                            get_class($this).": unable to remove orphaned mailarchive item {$file}"
                        );
                    }
                }
            }
        }

        Log::debug(
            get_class($this).': Housekeeper has completed removing orphaned mailarchive items'
        );

        return true;
    }

    /**
     * Walk through all jobs and queues to make sure they are working, including firing a testjob at them.
     *
     * @return bool
     */
    private function checkQueues()
    {
        Log::debug(
            get_class($this).': Housekeeper is starting queue checks'
        );

        $jobLimit = new Carbon('1 hour ago');

        $hangs = [];
        foreach (config('queue.queues') as $queue) {
            /*
             * Fire an test jobs into the abuseio queue selected.
             * Handling of the result is done at the QueueTest->failed() method
             */
            $this->dispatch(new QueueTest($queue));

            /*
             * Check all created jobs not to be older then 1 hour
             */
            $jobs = Job::where('queue', '=', $queue)->get();

            foreach ($jobs as $job) {
                $created = $job->created_at;

                if ($jobLimit->gt($created)) {
                    $hangs[] = $job;
                }
            }
        }
        /*
         * Send alarm on hanging jobs
         */
        $hangCount = count($hangs);
        if ($hangCount != 0) {
            Log::warning(
                get_class($this).": Housekeeper detected {$hangCount} jobs that are stuck in one or more queues!"
            );

            if (config('main.housekeeping.enable_queue_problem_alerts')) {
                AlertAdmin::send(
                    "Alert: There are {$hangCount} jobs that are stuck:".PHP_EOL.PHP_EOL.
                    implode(PHP_EOL, $hangs)
                );
            }
        }

        /*
         * Check for any kind of failed jobs, if any found start alarm bells
         */
        $failed = $this->laravel['queue.failer']->all();
        $failedCount = count($failed);
        if ($failedCount != 0) {
            // Reset object to string for reporting
            foreach ($failed as $key => $job) {
                $failed[$key] = implode(' ', get_object_vars($job));
            }

            Log::warning(
                get_class($this).": Housekeeper detected failed {$failedCount} jobs which need to be handled!"
            );

            if (config('main.housekeeping.enable_queue_problem_alerts')) {
                AlertAdmin::send(
                    "Alert: There are {$failedCount} jobs that have failed:".PHP_EOL.PHP_EOL.
                    implode(PHP_EOL, $failed)
                );
            }
        }

        Log::debug(
            get_class($this).': Housekeeper has completed queue checks'
        );

        if ($hangCount != 0 || $failedCount != 0) {
            return false;
        }

        return true;
    }

    /**
     * Walk through all tickets to see which need closing.
     *
     * @return bool
     */
    private function ticketsClosing()
    {
        Log::debug(
            get_class($this).': Housekeeper is starting to close old tickets'
        );

        $closeOlderThen = strtotime(config('main.housekeeping.tickets_close_after').' ago');
        $validator = Validator::make(
            [
                'mailarchive_remove_after' => $closeOlderThen,
            ],
            [
                'mailarchive_remove_after' => 'required|timestamp',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->messages();

            $message = '';
            foreach ($messages->all() as $messagePart) {
                $message .= $messagePart.PHP_EOL;
            }
            echo $message;

            return false;
        } else {
            $tickets = Ticket::where('status_id', '!=', 'CLOSED')->get();

            foreach ($tickets as $ticket) {
                /*
                 * If there is a ticket without an event we need to use the created_at field instead
                 * to prevent an index error on the event check
                 */
                if (empty($ticket->lastEvent[0])) {
                    $lastEventTimestamp = strtotime($ticket->created_at);
                } else {
                    $lastEventTimestamp = $ticket->lastEvent[0]->timestamp;
                }

                if ($lastEventTimestamp <= $closeOlderThen &&
                    strtotime($ticket->created_at) <= $closeOlderThen
                ) {
                    $ticket->update(
                        [
                            'status_id' => 'CLOSED',
                        ]
                    );
                }
            }
        }

        Log::debug(
            get_class($this).': Housekeeper has completed closing old tickets'
        );

        return true;
    }

    /**
     * prune old tickets.
     *
     * @return bool
     */
    private function ticketsPruning()
    {
        Log::debug(
            get_class($this).': Housekeeper is starting to remove old closed tickets'
        );

        $closeAfter = strtotime(config('main.housekeeping.closed_tickets_remove_after', 'now').' ago');
        if ($closeAfter !== false) {
            // valid timestamp

            $closedTickets = Ticket::withTrashed()
                ->where('status_id', '=', 'CLOSED')
                ->get();

            foreach ($closedTickets as $closedTicket) {
                $lastEventCollection = $closedTicket->lastEvent;
                if (empty($lastEventCollection) || empty($lastEventCollection->first())) {
                    $eventCreated = strtotime($closedTicket->updated_at);
                } else {
                    $lastEvent = $lastEventCollection->first();
                    $eventCreated = strtotime($lastEvent->created_at);
                }

                // skip this ticket if it has events newer then the timestamp
                if ($eventCreated > $closeAfter) {
                    continue;
                }

                // delete ticket
                $closedTicket->delete();

                // purge ticket, if we want it to be permanent deleted
                if (config('main.housekeeping.closed_tickets_remove_permanent', false)) {
                    $closedTicket->purge();
                }
            }
        }

        Log::debug(
            get_class($this).': Housekeeper has completed removing old tickets'
        );

        return true;
    }

    /**
     * Walk through mailarchive to see which need pruning.
     *
     * @return bool
     */
    private function mailarchivePruning()
    {
        Log::debug(
            get_class($this).': Housekeeper is starting to remove old mailarchive items'
        );

        $deleteOlderThen = strtotime(config('main.housekeeping.mailarchive_remove_after').' ago');
        $validator = Validator::make(
            [
                'mailarchive_remove_after' => $deleteOlderThen,
            ],
            [
                'mailarchive_remove_after' => 'required|timestamp',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->messages();

            $message = '';
            foreach ($messages->all() as $messagePart) {
                $message .= $messagePart.PHP_EOL;
            }
            echo $message;

            return false;
        } else {
            $evidences = Evidence::where('created_at', '<=', date('Y-m-d H:i:s', $deleteOlderThen))->get();

            foreach ($evidences as $evidence) {
                $evidence->delete();
            }
        }

        Log::debug(
            get_class($this).': Housekeeper has completed removing old mailarchive items'
        );

        return true;
    }
}
