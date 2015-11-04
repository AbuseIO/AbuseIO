<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:delete
                            {--id : x }
                            {--name : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes a role';

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
