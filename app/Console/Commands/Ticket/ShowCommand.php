<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Ticket;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "ticket";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'ticket_id',
            'submitter',
            'text',
            'hidden',
            'viewed',
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Ticket::Where("id", $this->argument("ticket"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'ticket',
                InputArgument::REQUIRED,
                'Use the id for a ticket to show it.')
        ];
    }
}
