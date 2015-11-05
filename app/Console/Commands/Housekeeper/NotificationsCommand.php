<?php

namespace AbuseIO\Console\Commands\Housekeeper;

use Illuminate\Console\Command;
use Carbon;
use AbuseIO\Jobs\Notification;

class NotificationsCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'housekeeper:notifications
                            {--list : Shows a list of pending notifications }
                            {--send : Sends out pending notifications manually }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'List of send out pending notifications';

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
     * @return mixed
     */
    public function handle()
    {

    }
}
