<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Job;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ListCommand extends AbstractListCommand
{
    protected $filterArguments = ["id", "queue"];

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [
        'Queue',
        'Jobs',
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

        foreach ($list as $queue) {
            $result[] = [
                $queue,
                Job::where('queue', '=', $queue)->count(),
            ];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        $queues = config('queue.queues');

        // todo build filter, return filtered
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return config('queue.queues');
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "queue";
    }
}
