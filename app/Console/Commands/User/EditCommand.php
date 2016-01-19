<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use AbuseIO\Models\Account;
use Validator;
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
                            {--firstname= : The new first name of the users account }
                            {--lastname= : The new last name of the users account }
                            {--language= : The default language for the users account, in country code }
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
     * @return boolean
     */
    public function handle()
    {
        if (empty($this->option('user'))) {
            $this->warn('the required user argument was not passed, try --help');
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

        // Apply changes to the user object
        if (!empty($this->option('email'))) {
            $user->email = $this->option('email');
        }
        if (!empty($this->option('password'))) {
            $user->password = $this->option('password');
        }
        if (!empty($this->option('autopassword'))) {
            $generatedPassword = substr(md5(rand()), 0, 8);
            $this->info("Using auto generated password: {$generatedPassword}");
            $user->password = $generatedPassword;
        }
        if (!empty($this->option('firstname'))) {
            $user->first_name = $this->option('firstname');
        }
        if (!empty($this->option('lastname'))) {
            $user->last_name = $this->option('lastname');
        }
        if (!empty($this->option('account'))) {
            $account = Account::where('name', '=', $this->option('account'))->first();
            if (!is_object($account)) {
                $this->error("The account named {$this->option('account')} was not found");
                return false;
            }

            $user->account_id = $account->id;
        }
        if (!empty($this->option('language'))) {
            $user->locale = $this->option('language');
        }
        if (!empty($this->option('disable'))) {
            $user->disabled = true;
        }
        if (!empty($this->option('enable'))) {
            $user->disabled = false;
        }

        // Validate the changes
        $validationUser = $user->toArray();
        $validationUser['password'] = empty($this->option('password')) ? $generatedPassword : $this->option('password');
        $validationUser['password_confirmation'] = $validationUser['password'];

        $validation = Validator::make($validationUser, User::updateRules($user));

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the user due to validation warnings');

            return false;
        }

        // Save the object
        $user->save();

        $this->info("User has been successfully updated");

        return true;
    }
}
