<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Contact;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand.
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAllowedArguments()
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getObjectByArguments()
    {
        return Contact::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for a contact to delete it.'
            ),
        ];
    }
}
