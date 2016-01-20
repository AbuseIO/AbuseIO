<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Account;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class CreateCommand extends AbstractCreateCommand
{
    public function getArgumentsList()
    {
        return new InputDefinition([
            new InputArgument('name', null, 'account name'),
            new InputArgument("brand_id", null, "brand id"),
            //new InputArgument('description', null, 'description'),
            new InputArgument('disabled', null, 'true|false, Set the account to be enabled', false),
        ]);
    }

    public function getAsNoun()
    {
        return "account";
    }

    protected function getModelFromRequest()
    {
        $account = new Account();

        $account->name = $this->argument('name');
        $account->brand_id = $this->argument('brand_id');
        //$account->description = $this->argument('description');
        $account->disabled = $this->argument('disabled') === "true" ? true : false;

        return $account;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Account::createRules($model));
    }
}

