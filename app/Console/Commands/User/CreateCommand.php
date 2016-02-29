<?php

namespace AbuseIO\Console\Commands\User;


use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Validator;

class CreateCommand extends AbstractCreateCommand
{
    private $password;

    public function getArgumentsList()
    {
        return new InputDefinition([
            new InputArgument('email', null, 'The emailaddres for the account.', null),
            new InputArgument('password', null, 'The new password for the account', null),
            new InputArgument('first_name', null,'the new first name of the users account.', null),
            new inputargument('last_name', null, 'the new last name of the users account', null),
            new inputargument('language', null, 'the default language for the users account, in country code ', null),
            new inputargument('account', null, 'the new account name where this user is linked to', null),
            new inputargument('disabled', null, 'set the new account status to be disabled', 'false')
        ]);
    }

    public function getAsNoun()
    {
        return "user";
    }

    protected function getModelFromRequest()
    {
        $user = new User;

        $user->first_name = $this->argument('first_name');
        $user->last_name = $this->argument('last_name');
        $user->email = $this->argument('email');

        $account = $this->findAccountByName($this->argument('account'));
        $user->account_id = $account->id;

        $user->password = $this->getPassword();
        $user->locale = $this->argument('language');
        $user->disabled = castStringToBool($this->argument('disabled'));

        return $user;
    }

    protected function getPassword()
    {
        $this->password = $this->argument('password');

        if (empty($this->password)) {
            $this->password = substr(md5(rand()), 0, 8);

            $this->info(
                sprintf('Using auto generated password: %s', $this->password)
            );
        };

        return $this->password;
    }

    protected function getValidator($model)
    {
        $arr = $model->toArray();

        $arr['password'] = $this->password;
        $arr['password_confirmation'] = $this->password;

        return Validator::make($arr, User::createRules($model));
    }

    protected function findAccountByName($name)
    {
        $account = Account::where('id', $name)->orWhere('name', $name)->first();

        if ($account === null) {
            $account = Account::find(array("name" => 'Default'))->first();

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

