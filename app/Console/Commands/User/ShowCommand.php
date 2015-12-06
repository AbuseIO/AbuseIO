<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:show
                            {email? : Use the email/login to show user details [OR] }
                            {id? : Use the user id to show user details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of an user';

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
        if (empty($this->option('email')) &&
            empty($this->option('id'))
        ) {
            $this->warn('no email or id argument was passed, try help');
            return false;
        }
    }
}
