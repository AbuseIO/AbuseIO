<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Evidence;
use Illuminate\Filesystem\Filesystem;
use Validator;
use Artisan;
use Carbon;
use Log;

class RunCommand extends Command
{

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
     * @return void
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

    /*
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

    /*
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

    /*
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
}
