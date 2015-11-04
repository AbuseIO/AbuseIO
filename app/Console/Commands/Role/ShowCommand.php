<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:show
                            {--id : x }
                            {--name : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of a role';

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
