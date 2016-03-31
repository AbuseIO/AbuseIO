<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Ticket;
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
        return 'ticket';
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
        return Ticket::find($this->argument('id'));
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
                'Use the id for a ticket to delete it.'
            ),
        ];
    }
}
