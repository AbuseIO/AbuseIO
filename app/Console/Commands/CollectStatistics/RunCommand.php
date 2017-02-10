<?php

namespace AbuseIO\Console\Commands\CollectStatistics;

use AbuseIO\Jobs\GenerateTicketsGraphPoints;
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
    protected $signature = 'statistics:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run statistics collector';

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
        Log::debug(
            get_class($this).': KNOCK KNOCK statistics collector'
        );

        $this->dispatch(new GenerateTicketsGraphPoints());

        return true;
    }
}
