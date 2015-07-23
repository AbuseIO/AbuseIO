<?php

namespace AbuseIO\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        'AbuseIO\Console\Commands\EmailReceiveCommand'
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
