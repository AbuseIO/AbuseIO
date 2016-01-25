<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Job;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ShowCommand extends AbstractShowCommand2
{

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["name"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'Queue',
            'Job name',
            'created',
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return $this->findWithCondition($this->argument('queue'));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'queue',
                InputArgument::REQUIRED,
                'Use the name for a queue to show it.')
        ];
    }

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        /*
         * TODO:
         * Zie headers, list laat alleen een overzicht van de gebruikte queues zien en het aantal jobs
         * per queue. Details per queue is te zien met 'show' command.
         */
        $result = [];

        foreach ($list as $job) {
            $payload = unserialize(json_decode($job->payload)->data->command);
            $method = class_basename($payload);

            $result[] = [
                $job->id,
                $job->queue,
                $method,
                $job->created_at,
            ];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Job::where('queue', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Job::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "queue";
    }
}
