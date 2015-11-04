<?php

namespace AbuseIO\Console\Commands\Permission;

use Illuminate\Console\Command;
use Carbon;

class AssignCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'permission:assign
                            {--name : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Assign a permission to a role';

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
