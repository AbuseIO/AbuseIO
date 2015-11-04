<?php

namespace AbuseIO\Console\Commands\Permission;

use Illuminate\Console\Command;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'permission:list
                            {--filter : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available permissions';

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
     * @return mixed
     */
    public function handle()
    {
    }
}
