<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use Carbon;

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
        // TODO - Walk thru all collectors to gather information.

        // TODO - Walk thru all tickets to see which need closing

        // TODO - Send out all notifications / Call housekeeper:notifications(send) ?

        return true;

    }
}
