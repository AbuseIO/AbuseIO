<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Contact;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

// TODO logo must be resolved, cann't resolve logo from CLI maybe a default or change required in model?

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCreateCommand
{
    /**
     * {@inheritdoc}.
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('name', null, 'Name'),
                new InputArgument('reference', null, 'Reference'),
                new InputArgument('account_id', null, 'Account id'),
                new InputArgument('enabled', null, 'enabled'),
                new InputArgument('email', null, 'Email address'),
                new InputArgument('api_host', null, 'Api host'),
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
        $contact = new Contact();

        $contact->name = $this->argument('name');
        $contact->reference = $this->argument('reference');
        $contact->account_id = $this->argument('account_id');
        $contact->enabled = $this->argument('enabled') === 'true' ? true : false;
        $contact->email = $this->argument('email');
        $contact->api_host = $this->argument('api_host');

        return $contact;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Contact::createRules());
    }
}
