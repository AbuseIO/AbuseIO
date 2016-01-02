<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Job;
use Carbon;

/**
 * Class ShowCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ShowCommand extends AbstractShowCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'queue:show {name}
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Show all pending jobs from a specific queue';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [ 'ID', 'Queue', 'Worker', 'Attempts', 'Created' ];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = [ 'id', 'queue', 'method', 'attempts', 'created_at' ];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        foreach ($list as $key => $job) {
            $payload = unserialize(json_decode($job->payload)->data->command);
            $method = class_basename($payload);

            $result[] = [
                $job->id,
                $job->queue,
                $method,
                $job->attempts,
                $job->created_at
            ];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Job::where('queue', '=', "{$this->argument('name')}")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        // No such thing?
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "jobs";
    }
}
