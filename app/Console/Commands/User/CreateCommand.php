<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use Carbon;
use AbuseIO\Models\User;
use AbuseIO\Models\Account;
use Validator;

class CreateCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:create
                            {--email= : The e-mail address and login username }
                            {--password= : The password for the account }
                            {--firstname=Unknown : The first name of the users account }
                            {--lastname=Unknown : The last name of the users account }
                            {--language=en : The default language for the users account, in country code }
                            {--account=default : The account name where this user is linked to }
                            {--disabled=false : Set the account to be disabled }
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
     * @return boolean
     */
    public function handle()
    {
        $generatedPassword = substr(md5(rand()), 0, 8);
        if (empty($this->option('password'))) {
            $this->info("Using auto generated password: {$generatedPassword}");
        }

        if (empty($this->option('account'))) {
            $account = Account::where('name', '=', 'default')->first();
        } else {
            $account = Account::where('name', '=', $this->option('account'))->first();
            if (!is_object($account)) {
                $this->error("The account named {$this->option('account')} was not found");
                return false;
            }
        }

        $user = new User();

        $user->email         = empty($this->option('email')) ? false : $this->option('email');
        $user->password      = empty($this->option('password')) ? $generatedPassword : $this->option('password');
        $user->first_name    = $this->option('firstname');
        $user->last_name     = $this->option('lastname');
        $user->locale        = $this->option('language');
        $user->account_id    = $account->id;
        $user->disabled      = $this->option('disabled');

        $validation = Validator::make($user->toArray(), User::createRules());

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the user due to validation warnings');

            return false;
        }

        if (!$user->save()) {
            $this->error('Failed to save the user into the database');

            return false;
        }

        $this->info("The user {$this->option('email')} has been created");

        return true;
    }
}
