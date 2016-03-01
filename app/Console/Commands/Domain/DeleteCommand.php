<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Domain;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand
 * @package AbuseIO\Console\Commands\Domain
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "domain";
    }

    /**
     * {@inheritdoc }
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inheritdoc }
     */
    protected function getObjectByArguments()
    {
        return Domain::find($this->argument("id"));
    }

    /**
     * {@inheritdoc }
     */
    protected function defineInput()
    {
        return array(
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for a domain to delete it.'
            )
        );
    }
}
