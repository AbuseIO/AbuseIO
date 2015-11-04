<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use Carbon;

class CreateCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:create
                            {--email : x }
                            {--password : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Creates a new user';

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
