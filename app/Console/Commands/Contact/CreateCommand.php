<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Contact;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
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
                new InputArgument('name', InputArgument::REQUIRED, 'Name'),
                new InputArgument('reference', InputArgument::REQUIRED, 'Reference'),
                new InputArgument('account_id', InputArgument::REQUIRED, 'Account id'),
                new InputArgument('enabled', null, 'enabled'),
                new InputArgument('email', null, 'Email address'),
                new InputArgument('api_host', null, 'Api host'),
                new InputOption('with_api_key', null, InputOption::VALUE_NONE, 'generates api key for account'),
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

        if ($this->option('with_api_key')) {
            $contact->token = generateApiToken();
        }

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
