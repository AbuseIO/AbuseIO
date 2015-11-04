<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use Carbon;

class EditCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:edit
                            {--email : x }
                            {--id : x }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Changes information from a user';

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
