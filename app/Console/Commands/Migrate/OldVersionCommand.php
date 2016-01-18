<?php

namespace AbuseIO\Console\Commands\Migrate;

use Illuminate\Console\Command;
use Carbon;

/**
 * Class OldVersionCommand
 * @package AbuseIO\Console\Commands\Migrate
 */
class OldVersionCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'migrate:oldversion
                            {--p|prepare : Prepares the migration by building all required caches}
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'List of send out pending notifications';

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
        if (!empty($this->option('prepare'))) {
            echo "bart";
        }
    }
}
