<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreUserRequest;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Illuminate\Console\Command;
use Validator;

class CreateUser extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email : The email address for the account.}
                                        {account? : The new account name where this user is linked to.}
                                        {--password= : The new password for the account.}
                                        {--first_name=dummy : The first name of the user\'s account.}
                                        {--last_name=dummy : The last name of the user\'s account.}
                                        {--language=en : The default language for the user\'s account, in country code.}
                                        {--disabled=false : Set the new account status to be disabled.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user in the system. If no account is specified, the user will be linked to the default account. If no password is specified, a random password will be generated and shown in the console output.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentEmail = $this->argument('email');
        $inputArgumentAccount = $this->argument('account');
        $inputOptionPassword = $this->option('password');
        $inputOptionFirstName = $this->option('first_name');
        $inputOptionLastName = $this->option('last_name');
        $inputOptionLanguage = $this->option('language');
        $inputOptionDisabled = castStringToBool($this->option('disabled'));

        if ($inputArgumentAccount) {
            $account = Account::where('id', $inputArgumentAccount)
                ->orWhere('name', $inputArgumentAccount)
                ->first();
        }

        if (!$account || empty($inputArgumentAccount)) {
            $account = Account::where('name', 'Default')->first();
            $this->info(
                sprintf("No account was found for given account name so '%s' was used", $account->name)
            );
        }

        if (empty($inputOptionPassword)) {
            $inputOptionPassword = generatePassword();

            $this->info(
                sprintf('Using auto generated password: %s', $inputOptionPassword)
            );
        }

        $user = User::make([
            'email' => $inputArgumentEmail,
            'account_id' => $account->id,
            'first_name' => $inputOptionFirstName,
            'last_name' => $inputOptionLastName,
            'password' => $inputOptionPassword,
            'language' => $inputOptionLanguage,
            'disabled' => $inputOptionDisabled,
        ]);

        $userArray = $user->toArray();
        $userArray['locale'] = $inputOptionLanguage;
        $userArray['password'] = $inputOptionPassword;
        $userArray['password_confirmation'] = $inputOptionPassword;

        $validator = Validator::make($userArray, new StoreUserRequest()->rules());

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(' ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if (!$user->save()) {
            $this->error('Failed to save the user.');
            return $this->getSaveFailedExitCode();
        }

        $this->info('The user has been created (id: ' . $user->id . ').');
        return $this->getSuccessExitCode();
    }
}
