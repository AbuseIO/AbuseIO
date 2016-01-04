<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Evidence;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use AbuseIO\Jobs\QueueTest;
use AbuseIO\Jobs\AlertAdmin;
use AbuseIO\Models\Job;
use Validator;
use Log;
use Carbon;

/**
 * Class RunCommand
 * @package AbuseIO\Console\Commands\Housekeeper
 */
class RunCommand extends Command
{

    use DispatchesJobs;

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'housekeeper:run
    ';

    /**
     * The console command description.
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
     * @return boolean
     */
    public function handle()
    {
        Log::debug(
            '(JOB ' . getmypid() . ') KNOCK KNOCK! Housekeeping'
        );

        /*
         * Check all queue's their status
         */
        Log::info(
            '(JOB ' . getmypid() . ') Housekeeper is starting queue checks'
        );
        $this->checkQueues();

        /*
         * Walk thru all tickets to see which need closing
         */
        if (config('main.housekeeping.tickets_close_after') !== false) {
            Log::info(
                '(JOB ' . getmypid() . ') Housekeeper is starting to close old tickets'
            );
            $this->ticketsClosing();
        }

        /*
         * Walk thru mailarchive to see which need pruning
         */
        if (config('main.housekeeping.mailarchive_remove_after') !== false) {
            Log::info(
                '(JOB ' . getmypid() . ') Housekeeper is starting to remove old mailarchive items'
            );
            $this->mailarchivePruning();
        }

        return true;

    }

    /**
     * Walk thru all jobs and queues to make sure they are working, including firing a testjob at them
     *
     * @return boolean
     */
    private function checkQueues()
    {
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
                    $hangs [] = $job;
                }
            }
        }
        /*
         * Send alarm on hanging jobs
         */
        if (count($hangs) != 0) {
            AlertAdmin::send(
                "Alert: There are " . count($hangs) . " jobs that are stuck:" . PHP_EOL . PHP_EOL .
                implode(PHP_EOL, $hangs)
            );
        }

        /*
         * Check for any kind of failed jobs, if any found start alarm bells
         */
        $failed = $this->laravel['queue.failer']->all();
        if (count($failed) != 0) {
            AlertAdmin::send(
                "Alert: There are " . count($failed) . " jobs that have failed:" . PHP_EOL . PHP_EOL .
                implode(PHP_EOL, $failed)
            );
        }

        if (count($failed) != 0 || count($hangs) != 0) {
            return false;
        }

        return true;
    }

    /**
     * Walk thru all tickets to see which need closing
     *
     * @return boolean
     */
    private function ticketsClosing()
    {
        $closeOlderThen = strtotime(config('main.housekeeping.tickets_close_after') . " ago");
        $validator = Validator::make(
            [
                'mailarchive_remove_after'    => $closeOlderThen,
            ],
            [
                'mailarchive_remove_after'    => 'required|timestamp',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->messages();

            $message = '';
            foreach ($messages->all() as $messagePart) {
                $message .= $messagePart . PHP_EOL;
            }
            echo $message;

            return false;
        } else {

            $tickets = Ticket::where('status_id', '!=', '2')->get();

            foreach ($tickets as $ticket) {
                if ($ticket->lastEvent[0]->timestamp <= $closeOlderThen) {
                    $ticket->update(
                        [
                            'status_id' => 2,
                        ]
                    );
                }
            }
        }

        return true;
    }

    /**
     * Walk thru mailarchive to see which need pruning
     *
     * @return boolean
     */
    private function mailarchivePruning()
    {
        $deleteOlderThen = strtotime(config('main.housekeeping.mailarchive_remove_after') . " ago");
        $validator = Validator::make(
            [
                'mailarchive_remove_after'    => $deleteOlderThen,
            ],
            [
                'mailarchive_remove_after'    => 'required|timestamp',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->messages();

            $message = '';
            foreach ($messages->all() as $messagePart) {
                $message .= $messagePart . PHP_EOL;
            }
            echo $message;

            return false;
        } else {

            $evidences = Evidence::where('created_at', '<=', date('Y-m-d H:i:s', $deleteOlderThen))->get();

            $filesystem = new Filesystem;

            foreach ($evidences as $evidence) {
                $path = storage_path() . '/mailarchive/';
                $filesystem->delete($path . $evidence->filename);
                $evidence->delete();
            }
        }

        return true;
    }
}
