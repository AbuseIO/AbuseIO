<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Collectors\Factory as CollectorFactory;
use AbuseIO\Jobs\CollectorProcess;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;

/**
 * Class RunAllCommand.
 */
class RunAllCommand extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'collector:runall
                                {--noqueue : Do not queue the collectors, but directly handle it }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all enabled collection processes';

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
        Log::info(
            get_class($this).': '.
            'Starting a collection run for all enabled collectors'
        );

        $collectors = collectorFactory::getCollectors();

        foreach ($collectors as $collectorName) {
            if (config("collectors.{$collectorName}.collector.enabled") === true) {
                if ($this->option('noqueue') == true) {
                    // In debug mode we don't queue the job
                    Log::debug(
                        get_class($this).': '.
                        'Queuing disabled. Directly handling message file: '.$collectorName
                    );

                    $processer = new CollectorProcess($collectorName);
                    $processer->handle();
                } else {
                    Log::info(
                        get_class($this).': '.
                        'Pushing collector into queue: '.$collectorName
                    );
                    $this->dispatch(new CollectorProcess($collectorName));
                }
            }
        }

        Log::info(
            'Completed collections startup for all enabled collectors'
        );

        return true;
    }
}
