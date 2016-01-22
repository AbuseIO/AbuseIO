<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Account;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{
    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('id', null, 'Account id to edit'),
            new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'brand name'),
            new InputOption('company_name', null, InputOption::VALUE_OPTIONAL,  'company name'),
            new InputOption('introduction_text', null, InputOption::VALUE_OPTIONAL, 'Introduction text'),
        ]);
    }

    public function getAsNoun()
    {
        return 'brand';
    }

    protected function getModelFromRequest()
    {
        $account = Account::find($this->argument('id'));

        $this->updateFieldWithOption($account, 'name');
        $this->updateFieldWithOption($account, 'company_name');
        $this->updateFieldWithOption($account, 'introduction_text');

        return $account;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Account::updateRules($model));
    }
}
