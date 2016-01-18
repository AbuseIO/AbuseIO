<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Event;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class CreateCommand extends AbstractCreateCommand
{
    // TODO timestamp argument, not sure why that is here I put it to now for now.
    // TODO information should be json, don't know how to add this via cli. "{}" is the only one that works

    public function getArgumentsList()
    {
        return new InputDefinition([
            new InputArgument('ticket_id', null, 'Ticket id'),
            new InputArgument('evidence_id', null, 'Evidence id'),
            new InputArgument('source', null, 'Source'),
            new InputArgument('information', null, 'Information'),
        ]);
    }

    public function getAsNoun()
    {
        return 'event';
    }

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

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Event::createRules($model));
    }
}
