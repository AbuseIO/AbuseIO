<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Event;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand.
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}.
     */
    protected function getAsNoun()
    {
        return 'event';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['id'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return ['id', 'ticket_id', 'evidence_id', 'source', 'timestamp', 'information'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return Event::where('id', $this->argument('event'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'event',
                InputArgument::REQUIRED,
                'Use the id for a event to show it.'
            ),
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        foreach ($result as $index => $row) {
            if ($row[0] == 'Information') {
                unset($result[$index]);
            }
        }

        $result[] = ['Evidence File',  $model->evidence->filename];

        $result[] = ['[Information Field]', '[Information Data]'];
        foreach (json_decode($model->information) as $field => $data) {
            $result[] = [$field, $data];
        }

        return $result;
    }
}
