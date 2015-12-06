<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:delete
                            {--email= : Use the email/login to show user details [OR] }
                            {--id= : Use the user id to show user details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes an user (without confirmation!)';

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

        $user = false;
        if (!empty($this->option('email'))) {
            $user = User::where('email', $this->option('email'))->first();
        }

        if (!empty($this->option('id'))) {
            $user = User::find($this->option('id'));
        }

        if (!is_object($user)) {
            $this->error('Unable to find user with this criteria');
            return false;
        }

        if (!$user->delete()) {
            $this->error('Unable to delete user from the system');
            return false;
        }

        $this->info('The user has been deleted from the system');

        return true;
    }
}
