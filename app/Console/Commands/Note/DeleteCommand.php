<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Note;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand
 * @package AbuseIO\Console\Commands\Account
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "note";
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
        return Note::find($this->argument("id"));
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
                'Use the id for a note to delete it.'
            )
        );
    }
}
