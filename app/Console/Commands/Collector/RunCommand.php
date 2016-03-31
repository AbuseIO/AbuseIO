<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Jobs\CollectorProcess;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;

/**
 * Class RunCommand.
 */
class RunCommand extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'collector:run {name}
                            {--debug : Do not create events, just display the results }
                            {--noqueue : Do not queue the collectors, but directly handle it }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run collection processes for a specific collector';

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
     * @return bool
     */
    public function handle()
    {
        if ($this->option('noqueue') == true) {
            // In debug mode we don't queue the job
            Log::debug(
                get_class($this).': '.
                'Queuing disabled. Directly handling message file: '.$this->argument('name')
            );

            $processer = new CollectorProcess($this->argument('name'));
            $processer->handle();
        } else {
            Log::info(
                get_class($this).': '.
                'Pushing collector into queue: '.$this->argument('name')
            );
            $this->dispatch(new CollectorProcess($this->argument('name')));
        }

        return true;
    }
}
