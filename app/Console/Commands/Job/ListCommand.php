<?php

namespace AbuseIO\Console\Commands\Job;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Job;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Job
 */
class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'job:list
                            {--filter= : Applies a filter on the job id or queue }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available jobs';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id',
        'Queue',
        'Payload',
        'Attempts',
        'Reserved',
        'Reserved at',
        'Available at',
        'Created at'];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $job  \AbuseIO\Models\Job|null */

        foreach ($list as $job) {
            $result[] = [
                $job->id,
                $job->queue,
                $job->payload,
                $job->attempts,
                $job->reserved,
                $job->reserved_at,
                $job->available_at,
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
        return Job::where('id',  $filter)
                ->orWhere("queue", "like", '%'.$filter.'%')
                ->get();
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
        return "job";
    }
}

