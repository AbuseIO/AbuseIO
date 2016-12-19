<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Domain;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

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
                new InputArgument('name', InputArgument::REQUIRED, 'domain name'),
                new InputArgument('contact_id', InputArgument::REQUIRED, 'the contact_id'),
                new InputArgument('enabled', null, 'true|false, Set the account to be enabled', false),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'domain';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        $domain = new Domain();

        $domain->contact_id = $this->argument('contact_id');
        $domain->name = $this->argument('name');
        $domain->enabled = $this->argument('enabled') === 'true' ? true : false;

        return $domain;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Domain::createRules());
    }
}
