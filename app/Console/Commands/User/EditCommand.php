<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Validator;

/**
 * Class EditCommand.
 */
class EditCommand extends AbstractEditCommand
{
    /**
     * @var
     */
    private $updatedPassword;

    /**
     * {@inheritdoc}.
     */
    public function getOptionsList()
    {
        return new InputDefinition(
            [
                new inputArgument('user', inputArgument::REQUIRED, 'The user id or e-mail of you want to edit'),
                new InputOption('email', null, InputOption::VALUE_OPTIONAL, 'The new e-mail address and login username'),
                new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The new password for the account '),
                new InputOption('autopassword', null, InputOption::VALUE_NONE, 'Generate a new password and set it for the account'),
                new InputOption('first_name', null, InputOption::VALUE_OPTIONAL, 'The new first name of the users account.'),
                new InputOption('last_name', null, InputOption::VALUE_OPTIONAL, 'The new last name of the users account'),
                new InputOption('language', null, InputOption::VALUE_OPTIONAL, 'The default language for the users account, in country code '),
                new InputOption('account', null, InputOption::VALUE_OPTIONAL, 'The new account name where this user is linked to'),
                new InputOption('disable', null, InputOption::VALUE_NONE, 'Set the new account status to be disabled'),
                new InputOption('enable', null, InputOption::VALUE_NONE, 'Set the new account status to be enabled'),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        return User::where('id', $this->argument('user'))
                ->orWhere('email', $this->argument('user'))
            ->first();
    }

    /**
     * {@inheritdoc}.
     */
    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'first_name');
        $this->updateFieldWithOption($model, 'last_name');
        $this->updateFieldWithOption($model, 'email');

        $this->handleLanguageUpdate($model);

        $this->handleEnableDisable($model);

        if (!empty($this->option('account'))) {
            $newAccount = Account::find($this->option('account'));
            if (null === $newAccount) {
                $this->error('Unable to find account with this criteria');

                return false;
            }
            $model->account->is($newAccount);
        }

        $this->handlePasswordUpdate($model);

        return true;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        $user = $model->toArray();

        if ($this->updatedPassword) {
            $user['password'] = $this->updatedPassword;
            $user['password_confirmation'] = $this->updatedPassword;
        }

        return Validator::make($user, User::updateRules($model));
    }

    /**
     * @param $model
     */
    protected function handleEnableDisable($model)
    {
        if ($this->option('enable')) {
            /* @var User $model */
            $model->disabled = false;
        }

        if ($this->option('disable')) {
            /* @var User $model */
            $model->disabled = true;
        }
    }

    /**
     * @param $model
     */
    protected function handlePasswordUpdate($model)
    {
        if (!empty($this->option('password'))) {
            $model->password = $this->option('password');

            $this->updatedPassword = $this->option('password');
        }
        if (!empty($this->option('autopassword'))) {
            $generatedPassword = generatePassword();
            $this->info(
                "Using auto generated password: {$generatedPassword}"
            );
            $model->password = $generatedPassword;

            $this->updatedPassword = $generatedPassword;
        }
    }

    /**
     * @param $model
     */
    protected function handleLanguageUpdate($model)
    {
        if (!empty($this->option('language'))) {
            $model->locale = $this->option('language');
        }
    }
}
