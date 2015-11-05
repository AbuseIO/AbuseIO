<?php

namespace AbuseIO\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        'AbuseIO\Console\Commands\Receive\EmailCommand',

        'AbuseIO\Console\Commands\User\CreateCommand',
        'AbuseIO\Console\Commands\User\EditCommand',
        'AbuseIO\Console\Commands\User\ListCommand',
        'AbuseIO\Console\Commands\User\ShowCommand',
        'AbuseIO\Console\Commands\User\DeleteCommand',

        'AbuseIO\Console\Commands\Role\CreateCommand',
        'AbuseIO\Console\Commands\Role\ListCommand',
        'AbuseIO\Console\Commands\Role\ShowCommand',
        'AbuseIO\Console\Commands\Role\DeleteCommand',

        'AbuseIO\Console\Commands\Permission\ListCommand',
        'AbuseIO\Console\Commands\Permission\ShowCommand',
        'AbuseIO\Console\Commands\Permission\AssignCommand',
        'AbuseIO\Console\Commands\Permission\RevokeCommand',

        'AbuseIO\Console\Commands\Housekeeper\RunCommand',
        'AbuseIO\Console\Commands\Housekeeper\NotificationsCommand',

    ];

    /**
     * Schedule method
     * @param  Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    }
}
