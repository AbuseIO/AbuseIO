<?php namespace AbuseIO\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        'AbuseIO\Console\Commands\EmailReceiveCommand'
    ];

    protected function schedule(Schedule $schedule)
    {

    }
}
