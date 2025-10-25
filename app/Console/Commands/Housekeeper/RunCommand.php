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
     * @return int
     */
    public function handle(): int
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

        return Command::SUCCESS;
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

        Log::debug(
            get_class($this).': Housekeeper has completed queue checks'
        );

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
            get_class($this).': Housekeeper is closing tickets that are over time'
        );

        $closingperiod = 'PT'.config('main.housekeeping.tickets_close_after').'H';
        $tickets = Ticket::where('status_id', '=', Ticket::STATUS_OPEN)->get();

        foreach ($tickets as $ticket) {
            $lastnotified = $ticket->last_notify_count();
            $closingDate = $ticket->updated_at->add(new \DateInterval($closingperiod));

            if ($lastnotified > 3 && $ticket->updated_at->lt($closingDate)) {
                continue;
            }

            $ticket->status_id = Ticket::STATUS_CLOSED;
            if (!$ticket->save()) {
                Log::error(
                    get_class($this).": Housekeeper was unable to close the ticket {$ticket->id}"
                );
            }
        }

        Log::debug(
            get_class($this).': Housekeeper completed closing overdue tickets'
        );

        return true;
    }

    /**
     * Walk trough all closed tickets to see which need pruning.
     *
     * @return bool
     */
    private function ticketsPruning()
    {
        Log::debug(
            get_class($this).': Housekeeper is pruning closed tickets that are over time'
        );

        $removaldate = new Carbon('1 month ago');

        $tickets = Ticket::where('status_id', '=', Ticket::STATUS_CLOSED)
            ->where('updated_at', '<', $removaldate)
            ->get();

        foreach ($tickets as $ticket) {
            if (!$ticket->delete()) {
                Log::error(
                    get_class($this).": Housekeeper was unable to remove the ticket {$ticket->id}"
                );
            }
        }

        Log::debug(
            get_class($this).': Housekeeper completed pruning closed tickets that are over time'
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
            get_class($this).': Housekeeper is pruning mailarchive'
        );

        $removaldate = new Carbon('1 month ago');

        $evidences = Evidence::where('created_at', '<', $removaldate)
            ->get();

        foreach ($evidences as $evidence) {
            $event = Event::where('evidence_id', '=', $evidence->id)->get();

            if ($event->count() !== 0) {
                continue;
            }

            try {
                Storage::delete($evidence->filename);
            } catch (\Exception $e) {
                Log::error(
                    get_class($this).": Housekeeper was unable to remove the file {$evidence->filename}"
                );
            }

            if (!$evidence->delete()) {
                Log::error(
                    get_class($this).": Housekeeper was unable to remove evidence item {$evidence->id}"
                );
            }
        }

        Log::debug(
            get_class($this).': Housekeeper completed pruning mailarchive'
        );

        return true;
    }
}
