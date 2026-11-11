<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateUserRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Illuminate\Console\Command;
use Validator;

class EditUser extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:edit {user : The user id or e-mail of you want to edit}
        {--email= : The new e-mail address and login username}
        {--password= : The new password for the account }
        {--autopassword : Generate a new password and set it for the account}
        {--first_name= : The new first name of the users account.}
        {--last_name= : The new last name of the users account}
        {--language= : The default language for the users account, in country code }
        {--account= : The new account name where this user is linked to}
        {--disable : Set the new account status to be disabled}
        {--enable : Set the new account status to be enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing user in the system. You can use either the user id or e-mail address to specify the user you want to edit.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentUser = $this->argument('user');
        $inputOptionEmail = $this->option('email');
        $inputOptionPassword = $this->option('password');
        $inputOptionAutopassword = $this->option('autopassword');
        $inputOptionFirstName = $this->option('first_name');
        $inputOptionLastName = $this->option('last_name');
        $inputOptionLanguage = $this->option('language');
        $inputOptionAccount = $this->option('account');
        $inputOptionDisable = $this->option('disable');
        $inputOptionEnable = $this->option('enable');

        $user = User::where('id', $inputArgumentUser)
            ->orWhere('email', $inputArgumentUser)
            ->first();

        if (!$user) {
            $this->error('Unable to find user with this criteria');
            return $this->getNotFoundExitCode();
        }

        $account = Account::where('id', $inputOptionAccount)
            ->orWhere('name', $inputOptionAccount)
            ->first();

        if (!$account) {
            $account = Account::where('name', 'Default')->first();
            $this->info(
                sprintf("No account was found for given account name so '%s' was used", $account->name)
            );
        }

        $user->email = $inputOptionEmail ?? $user->email;
        $user->first_name = $inputOptionFirstName ?? $user->first_name;
        $user->last_name = $inputOptionLastName ?? $user->last_name;
        $user->account_id = $account->id ?? $user->account_id;
        $user->locale = $inputOptionLanguage ?? $user->locale;
        $user->disabled = $inputOptionDisable ? true : ($inputOptionEnable ? false : $user->disabled);

        if (empty($inputOptionPassword) && $inputOptionAutopassword) {
            $inputOptionPassword = generatePassword();

            $this->info(
                sprintf('Using auto generated password: %s', $inputOptionPassword)
            );
        }

        $user->password = $inputOptionPassword ?? $user->password;

        $request = new UpdateUserRequest();
        $request->merge(['id' => $user->id]);
        $validator = Validator::make($user->toArray(), $request->rules());

        if ($validator->fails()) {
            $this->error('Unable to update user due to validation errors:' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$user->update()) {
            $this->error('An error occurred while updating the user');
            return $this->getSaveFailedExitCode();
        }

        $this->info('The user has been updated');
        return $this->getSuccessExitCode();
    }
}
