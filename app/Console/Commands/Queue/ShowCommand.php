<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Job;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ShowCommand extends AbstractListCommand
{
    protected $filterArguments = [
        'queue'
    ];

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [
        'id',
        'Queue',
        'Job name',
        'created',
    ];

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

    /**
     * {@inherit docs}
     */
    public function setCommandName()
    {
        return 'jobs';
    }
}
