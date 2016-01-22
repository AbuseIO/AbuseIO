<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Contact;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{
    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('id', null, 'Contact id to edit'),
            new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'contact name'),
            new InputOption('reference', null, InputOption::VALUE_OPTIONAL,  'company name'),
            new InputOption('account_id', null, InputOption::VALUE_OPTIONAL, 'Account id'),
            new InputOption('enabled',  null, InputOption::VALUE_OPTIONAL,  'Enabled'),
            new InputOption('email',  null, InputOption::VALUE_OPTIONAL,  'Email'),
            new InputOption('api_host', null, InputOption::VALUE_OPTIONAL,  'Api host'),
        ]);
    }

    public function getAsNoun()
    {
        return 'contact';
    }

    protected function getModelFromRequest()
    {
        return Contact::find($this->argument('id'));
    }

    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'name');
        $this->updateFieldWithOption($model, 'reference');
        $this->updateFieldWithOption($model, 'account_id');
        $this->updateFieldWithOption($model, 'enabled');
        $this->updateFieldWithOption($model, 'mail');
        $this->updateFieldWithOption($model, 'api_host');
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Contact::updateRules($model));
    }
}
