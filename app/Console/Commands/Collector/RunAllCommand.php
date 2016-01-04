<?php

namespace AbuseIO\Console\Commands\Collector;

use Illuminate\Console\Command;
use AbuseIO\Collectors\Factory as CollectorFactory;
use Carbon;
use Log;

/**
 * Class RunAllCommand
 * @package AbuseIO\Console\Commands\Collector
 */
class RunAllCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'collector:runall
    ';

    /**
     * The console command description.
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
     * @return boolean
     */
    public function handle()
    {

        Log::info(
            '(JOB ' . getmypid() . ') Starting a collection run for all enabled collectors'
        );

        /*
        $collector = collectorFactory::create($this->argument('name'));

        if (!$collector) {
            $this->error(
                "The requested collector {$this->argument('name')} could not be started check logs for PID:"
                . getmypid()
            );
            return false;
        }

        $results = $collector->parse();

        print_r($results);
        */

        return true;
    }
}
