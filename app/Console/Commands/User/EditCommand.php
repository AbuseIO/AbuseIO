<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use Carbon;

class EditCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:edit
                            {--user= : The user id or e-mail of you want to change }
                            {--email= : The new e-mail address and login username }
                            {--password= : The new password for the account }
                            {--autopassword : Generate a new password and set it for the account }
                            {--firstname= : The new first name of the users account, default: Unknown }
                            {--lastname= : The new last name of the users account, default: Unknown }
                            {--account= : The new account name where this user is linked to }
                            {--disable : Set the new account status to be disabled }
                            {--enable : Set the new account status to be enabled }
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
        if (empty($this->option('user'))) {
            $this->warn('the required user argument was not passed, try help');
            return false;
        }

        $user = false;
        if (!is_object($user)) {
            $user = User::where('email', $this->option('user'))->first();
        }

        if (!is_object($user)) {
            $user = User::find($this->option('user'));
        }

        if (!is_object($user)) {
            $this->error('Unable to find user with this criteria');
            return false;
        }

        print_r($user->toArray());

        return true;
    }
}
