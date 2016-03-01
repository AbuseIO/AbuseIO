<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Event;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand
 * @package AbuseIO\Console\Commands\Event
 */
class CreateCommand extends AbstractCreateCommand
{
    // TODO timestamp argument, not sure why that is here I put it to now for now.
    // TODO information should be json, don't know how to add this via cli. "{}" is the only one that works

    /**
     * {@inheritdoc }
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('ticket_id', null, 'Ticket id'),
                new InputArgument('evidence_id', null, 'Evidence id'),
                new InputArgument('source', null, 'Source'),
                new InputArgument('information', null, 'Information'),
            ]
        );
    }

    /**
     * {@inheritdoc }
     */
    public function getAsNoun()
    {
        return 'event';
    }

    /**
     * {@inheritdoc }
     */
    protected function getModelFromRequest()
    {
        $event = new Event();

        $event->ticket_id = $this->argument('ticket_id');
        $event->evidence_id = $this->argument('evidence_id');
        $event->source = $this->argument('source');
        $event->timestamp = time();
        $event->information = $this->argument('information');

        return $event;
    }

    /**
     * {@inheritdoc }
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Event::createRules());
    }
}
