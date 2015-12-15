<?php

namespace AbuseIO\Console;

use AbuseIO\Models\Netblock;
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
        'AbuseIO\Console\Commands\Role\EditCommand',
        'AbuseIO\Console\Commands\Role\ListCommand',
        'AbuseIO\Console\Commands\Role\ShowCommand',
        'AbuseIO\Console\Commands\Role\DeleteCommand',
        'AbuseIO\Console\Commands\Role\AssignCommand',
        'AbuseIO\Console\Commands\Role\RevokeCommand',

        'AbuseIO\Console\Commands\Permission\ListCommand',
        'AbuseIO\Console\Commands\Permission\ShowCommand',
        'AbuseIO\Console\Commands\Permission\AssignCommand',
        'AbuseIO\Console\Commands\Permission\RevokeCommand',

        'AbuseIO\Console\Commands\Housekeeper\RunCommand',
        'AbuseIO\Console\Commands\Housekeeper\NotificationsCommand',

        'AbuseIO\Console\Commands\Netblock\ListCommand',
        'AbuseIO\Console\Commands\Netblock\ShowCommand',
        'AbuseIO\Console\Commands\Netblock\DeleteCommand',
        'AbuseIO\Console\Commands\Netblock\CreateCommand',
        'AbuseIO\Console\Commands\Netblock\EditCommand',

        'AbuseIO\Console\Commands\Domain\ListCommand',
//        'AbuseIO\Console\Commands\Domain\ShowCommand',
//        'AbuseIO\Console\Commands\Domain\DeleteCommand',
//        'AbuseIO\Console\Commands\Domain\CreateCommand',
//        'AbuseIO\Console\Commands\Domain\EditCommand',

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
