<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Account;
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
     * @return InputDefinition
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('name', InputArgument::REQUIRED, 'account name'),
                new InputArgument('brand_id', InputArgument::REQUIRED, 'brand id'),
                //new InputArgument('description', null, 'description'),
                new InputArgument('disabled', InputArgument::OPTIONAL, 'true|false, Set the account to be enabled'),
                new InputOption('with_api_key', null, InputOption::VALUE_NONE, 'generates api key for account'),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        $account = new Account();

        $account->name = $this->argument('name');
        $account->brand_id = $this->argument('brand_id');
        //$account->description = $this->argument('description');
        $account->disabled = $this->argument('disabled') === 'true' ? true : false;

        if ($this->option('with_api_key')) {
            $account->token = generateApiToken();
        }

        return $account;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Account::createRules());
    }
}
