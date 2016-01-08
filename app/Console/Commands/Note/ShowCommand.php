<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Note;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "note";
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
        return Note::Where("id", $this->argument("note"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'note',
                InputArgument::REQUIRED,
                'Use the id for a note to show it.')
        ];
    }
}
