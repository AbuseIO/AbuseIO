<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Validator;

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCreateCommand
{
    /**
     * @var
     */
    private $password;

    /**
     * {@inheritdoc}.
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('email', InputArgument::REQUIRED, 'The email address for the account.'),
                new inputArgument('account', InputArgument::OPTIONAL, 'The new account name where this user is linked to.', null),
                new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The new password for the account.'),
                new InputOption('first_name', null, InputOption::VALUE_OPTIONAL, 'The first name of the user\'s account.', 'dummy'),
                new InputOption('last_name', null, InputOption::VALUE_OPTIONAL, 'The last name of the user\'s account.', 'dummy'),
                new inputOption('language', null, InputOption::VALUE_OPTIONAL, 'The default language for the user\'s account, in country code.', 'en'),
                new inputOption('disabled', null, InputOption::VALUE_OPTIONAL, 'Set the new account status to be disabled.', 'false'),
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
        $user = new User();

        $user->first_name = $this->option('first_name');
        $user->last_name = $this->option('last_name');
        $user->email = $this->argument('email');

        $user->account_id = $this->findAccountByName($this->argument('account'))->id;

        $user->password = $this->getPassword();
        $user->locale = $this->option('language');
        $user->disabled = castStringToBool($this->option('disabled'));

        return $user;
    }

    /**
     * @return array|string
     */
    protected function getPassword()
    {
        $this->password = $this->option('password');

        if (empty($this->password)) {
            $this->password = generatePassword();

            $this->info(
                sprintf('Using auto generated password: %s', $this->password)
            );
        }

        return $this->password;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        $arr = $model->toArray();

        $arr['password'] = $this->password;
        $arr['password_confirmation'] = $this->password;

        return Validator::make($arr, User::createRules());
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    protected function findAccountByName($name)
    {
        $account = Account::where('id', $name)->orWhere('name', $name)->first();

        if ($account === null) {
            $account = Account::find(['name' => 'Default'])->first();

            if ($account === null) {
                $account = Account::all()->first();
            }

            $this->info(
                sprintf("No account was found for given account name so '%s' was used", $account->name)
            );
        }

        return $account;
    }
}
