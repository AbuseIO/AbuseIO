<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use Carbon;

class CreateCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:create
                            {--name : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Creates a new role';

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
