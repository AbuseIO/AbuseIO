<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Evidence;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;
use AbuseIO\Jobs\QueueTest;
use AbuseIO\Jobs\AlertAdmin;
use Illuminate\Support\Arr;
use Validator;
use Artisan;
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
                            {--noNotifications : Do not send out pending notifications }
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
        // TODO: #AIO-22 Create housekeeping - Walk thru all collectors to gather information.
        // TODO: Extra: Collectors should be kicked into a queue, only if there isn't one running yet with the same name

        /*
         * Checks for beanstalk queue
         */
        // TODO: Somehow check howlong a job is running? Or self-kill it in 1 hour?


        /*
         * Fire an test jobs into the abuseio queue selected.
         * Handling of the result is done at the QueueTest->failed() method
         */
        // Todo how to get a list of queues, make it fixed?
        //$this->dispatch(new QueueTest($queue));


        /*
         * Check for any kind of failed jobs
         */
        $jobs = $this->getFailedJobs();
        $jobsMask = "|%-8.8s |%-20.20s |%-35.35s |%-35.35s |%-30.30s |" . PHP_EOL . PHP_EOL;
        $jobsList[] = sprintf($jobsMask, 'ID', 'Connection', 'Queue', 'Class', 'Failed At');

        if (count($jobs) != 0) {
            foreach ($jobs as $job) {
                if (count($job) == 5) {
                    $jobsList[] = sprintf(
                        $jobsMask,
                        $job[0],
                        $job[1],
                        $job[2],
                        $job[3],
                        $job[4]
                    );
                }
            }

            if (count($jobsList) > 2) {
                AlertAdmin::send(
                    "Alert: There are " . count($jobs) . " jobs in the queue that have failed:" . PHP_EOL . PHP_EOL .
                    implode(PHP_EOL, $jobsList)
                );
            }
        }

        /*
         * Walk thru all tickets to see which need closing
         */
        if (config('main.housekeeping.tickets_close_after') !== false) {
            $this->ticketsClosing();
        }

        /*
         * Walk thru mailarchive to see which need pruning
         */
        if (config('main.housekeeping.mailarchive_remove_after') !== false) {
            $this->mailarchivePruning();
        }

        /*
         * Send out all notifications by calling the housekeeper notifications command
         */
        if ($this->option('noNotifications') !== true) {
            $this->sendNotifications();
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

    /**
     * Send out all notifications by calling the housekeeper notifications command
     *
     * @return boolean
     */
    private function sendNotifications()
    {
        $exitCode = Artisan::call(
            'housekeeper:notifications',
            [
                "--send" => true,
            ]
        );

        echo Artisan::output();

        return true;
    }

    /**
     * Compile the failed jobs into a displayable format.
     *
     * @return array
     */
    protected function getFailedJobs()
    {
        $results = [];

        foreach ($this->laravel['queue.failer']->all() as $failed) {
            $results[] = $this->parseFailedJob((array) $failed);
        }

        return array_filter($results);
    }

    /**
     * Parse the failed job row.
     *
     * @param  array  $failed
     * @return array
     */
    protected function parseFailedJob(array $failed)
    {
        $row = array_values(Arr::except($failed, ['payload']));

        array_splice($row, 3, 0, $this->extractJobName($failed['payload']));

        return $row;
    }

    /**
     * Extract the failed job name from payload.
     *
     * @param  string  $payload
     * @return string|null
     */
    private function extractJobName($payload)
    {
        $payload = json_decode($payload, true);

        if ($payload && (! isset($payload['data']['command']))) {
            return Arr::get($payload, 'job');
        }

        if ($payload && isset($payload['data']['command'])) {
            preg_match('/"([^"]+)"/', $payload['data']['command'], $matches);

            if (isset($matches[1])) {
                return $matches[1];
            } else {
                return Arr::get($payload, 'job');
            }
        }

        return null;
    }
}
