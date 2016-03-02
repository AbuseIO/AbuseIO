<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Event;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand
 * @package AbuseIO\Console\Commands\Event
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "event";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id", "name"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'ticket_id',
            'evidence_id',
            'source',
            'timestamp',
            'information'
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Event::where("id", $this->argument("event"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'event',
                InputArgument::REQUIRED,
                'Use the id for an event to show it.'
            )
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);
        $result[] = ["Evidences", $model->evidence->filename];

        return $result;
    }
}
