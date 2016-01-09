<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Job;
use Carbon;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ListCommand extends AbstractListCommand
{

   protected $filterArguments = ["name"];
    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [ 'Name', 'Jobs' ];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = [ ];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];

        foreach ($list as $queue) {

            $result[$queue] = [
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
        return config('queue.queues');
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
        return "Queues";
    }
}
