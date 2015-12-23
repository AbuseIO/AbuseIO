<?php

namespace AbuseIO\Console\Commands\Collector;

use Illuminate\Console\Command;
use AbuseIO\Collectors\Factory as CollectorFactory;
use Carbon;

class RunCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'collector:run {name}
                            {--debug : Do not create events, just display the results }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Run collection processes for a specific collector';

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
     * @return boolean
     */
    public function handle()
    {
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

        return true;
    }
}
