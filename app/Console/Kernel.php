<?php

namespace AbuseIO\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package AbuseIO\Console
 */
class Kernel extends ConsoleKernel
{
    protected $commands = [
        'AbuseIO\Console\Commands\App\InstallationID',

        'AbuseIO\Console\Commands\Migrate\OldVersionCommand',

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
        'AbuseIO\Console\Commands\Domain\ShowCommand',
        'AbuseIO\Console\Commands\Domain\DeleteCommand',
        'AbuseIO\Console\Commands\Domain\CreateCommand',
        'AbuseIO\Console\Commands\Domain\EditCommand',

        'AbuseIO\Console\Commands\Account\ListCommand',
        'AbuseIO\Console\Commands\Account\ShowCommand',
        'AbuseIO\Console\Commands\Account\DeleteCommand',
        'AbuseIO\Console\Commands\Account\CreateCommand',
        'AbuseIO\Console\Commands\Account\EditCommand',

        'AbuseIO\Console\Commands\Brand\ListCommand',
        'AbuseIO\Console\Commands\Brand\ShowCommand',
        'AbuseIO\Console\Commands\Brand\DeleteCommand',
        'AbuseIO\Console\Commands\Brand\CreateCommand',

        'AbuseIO\Console\Commands\Collector\ListCommand',
        'AbuseIO\Console\Commands\Collector\ShowCommand',
        'AbuseIO\Console\Commands\Collector\RunCommand',
        'AbuseIO\Console\Commands\Collector\RunAllCommand',

        'AbuseIO\Console\Commands\Queue\ListCommand',
        'AbuseIO\Console\Commands\Queue\ShowCommand',

        'AbuseIO\Console\Commands\Contact\ShowCommand',
        'AbuseIO\Console\Commands\Contact\ListCommand',
        'AbuseIO\Console\Commands\Contact\DeleteCommand',
        'AbuseIO\Console\Commands\Contact\CreateCommand',

        'AbuseIO\Console\Commands\Event\ShowCommand',
        'AbuseIO\Console\Commands\Event\DeleteCommand',
        'AbuseIO\Console\Commands\Event\ListCommand',
        'AbuseIO\Console\Commands\Event\CreateCommand',

        'AbuseIO\Console\Commands\Evidence\ShowCommand',
        'AbuseIO\Console\Commands\Evidence\DeleteCommand',
        'AbuseIO\Console\Commands\Evidence\ListCommand',
        'AbuseIO\Console\Commands\Evidence\CreateCommand',

        'AbuseIO\Console\Commands\Note\ShowCommand',
        'AbuseIO\Console\Commands\Note\DeleteCommand',
        'AbuseIO\Console\Commands\Note\ListCommand',
        'AbuseIO\Console\Commands\Note\CreateCommand',

        'AbuseIO\Console\Commands\Ticket\ShowCommand',
        'AbuseIO\Console\Commands\Ticket\DeleteCommand',
        'AbuseIO\Console\Commands\Ticket\ListCommand',
        'AbuseIO\Console\Commands\Ticket\CreateCommand',

    ];

    /**
     * Schedule method
     * @param  Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('housekeeper:run')->cron(config('main.housekeeping.housekeeper_cron'));

        $schedule->command('housekeeper:notifications --send')->cron(config('main.housekeeping.notifications_cron'));

        $schedule->command('collector:runall')->cron(config('main.housekeeping.collectors_cron'));
    }
}
