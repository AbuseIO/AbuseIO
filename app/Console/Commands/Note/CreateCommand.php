<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Note;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCreateCommand
{
    // TODO validation of file not working

    /**
     * {@inheritdoc}.
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('ticket_id', InputArgument::REQUIRED, 'Ticket id'),
                new InputArgument('submitter', InputArgument::REQUIRED, 'Submitter'),
                new InputArgument('text', InputArgument::REQUIRED, 'Text'),
                new InputArgument('viewed', null, 'viewed', false),
                new InputArgument('hidden', null, 'Hidden', false),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'note';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        $note = new Note();

        $note->ticket_id = $this->argument('ticket_id');
        $note->submitter = $this->argument('submitter');
        $note->text = $this->argument('text');
        $note->viewed = $this->argument('viewed') === 'true' ? true : false;
        $note->hidden = $this->argument('hidden') === 'true' ? true : false;

        return $note;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Note::createRules());
    }
}
