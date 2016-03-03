<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Account;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand
 * @package AbuseIO\Console\Commands\Account
 */
class CreateCommand extends AbstractCreateCommand
{
    /**
     * @return InputDefinition
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('name', InputArgument::REQUIRED, 'account name'),
                new InputArgument("brand_id", InputArgument::REQUIRED, "brand id"),
                //new InputArgument('description', null, 'description'),
                new InputArgument('disabled', InputArgument::OPTIONAL, 'true|false, Set the account to be enabled'),
            ]
        );
    }

    /**
     * {@inheritdoc }
     */
    public function getAsNoun()
    {
        return "account";
    }

    /**
     * {@inheritdoc }
     */
    protected function getModelFromRequest()
    {
        $account = new Account();

        $account->name = $this->argument('name');
        $account->brand_id = $this->argument('brand_id');
        //$account->description = $this->argument('description');
        $account->disabled = $this->argument('disabled') === "true" ? true : false;

        return $account;
    }

    /**
     * {@inheritdoc }
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Account::createRules());
    }
}
