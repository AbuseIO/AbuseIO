<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Contact;
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
     * {@inheritdoc}.
     */
    public function getOptionsList()
    {
        return new InputDefinition(
            [
                new inputArgument('id', InputArgument::REQUIRED, 'Contact id to edit'),
                new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'contact name'),
                new InputOption('reference', null, InputOption::VALUE_OPTIONAL, 'Reference'),
                new InputOption('account_id', null, InputOption::VALUE_OPTIONAL, 'Account id'),
                new InputOption('enabled', null, InputOption::VALUE_OPTIONAL, 'Enabled'),
                new InputOption('email', null, InputOption::VALUE_OPTIONAL, 'Email'),
                new InputOption('api_host', null, InputOption::VALUE_OPTIONAL, 'Api host'),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        return Contact::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'name');
        $this->updateFieldWithOption($model, 'reference');
        $this->updateFieldWithOption($model, 'account_id');
        $this->updateFieldWithOption($model, 'enabled');
        $this->updateFieldWithOption($model, 'mail');
        $this->updateFieldWithOption($model, 'api_host');

        return true;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        $data = $this->getModelAsArrayForDirtyAttributes($model);
        $updateRules = $this->getUpdateRulesForDirtyAttributes(Contact::updateRules($model));

        return Validator::make($data, $updateRules);
    }
}
