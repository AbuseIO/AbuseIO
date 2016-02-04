<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Event;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
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
        return Event::Where("id", $this->argument("event"));
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
                'Use the id for an event to show it.')
        ];
    }

    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        $result[] = ["Evidences", $model->evidence->filename];
//        /** @var \AbuseIO\Models\Evidence $evidence */
//        dd($model);
//
//        foreach ($model->evidences as $evidence) {
//            $result[] = [$evidence->id, $evidence->filename];
//        }
        return $result;
    }
}
