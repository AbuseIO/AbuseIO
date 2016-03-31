<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Job;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    protected $filterArguments = [
        'queue',
    ];

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = [
        'Queue',
        'Jobs',
    ];

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
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
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        $queues = config('queue.queues');

        $results = [];
        foreach ($queues as $queue) {
            if (preg_match("/$filter/", $queue)) {
                $results[] = $queue;
            }
        }

        return $results;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return config('queue.queues');
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'queue';
    }
}
