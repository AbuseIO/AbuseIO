<?php

namespace AbuseIO\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('housekeeper:run')->cron(config('main.housekeeping.housekeeper_cron'));

        $schedule->command('housekeeper:notifications --send')->cron(config('main.housekeeping.notifications_cron'));

        $schedule->command('collector:runall')->cron(config('main.housekeeping.collectors_cron'));

        $schedule->command('statistics:run')->cron(config('main.housekeeping.collect_statistics_cron'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
