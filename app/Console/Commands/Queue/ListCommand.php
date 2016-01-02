<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Job;
use Carbon;
use Queue;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'queue:list
                                {--filter= : Applies a filter on the queue name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Show all queue usage';

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
        $counters = [];
        $result = [];

        foreach ($list as $key => $job) {

            if (empty($counters[$job->queue])) {
                $counters[$job->queue] = 0;
            }

            $counters[$job->queue]++;

            $result[$job->queue] = [
                $job->queue,
                $counters[$job->queue]
            ];
        }

        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Job::where('queue', 'like', "%{$this->option('filter')}%")->get();
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
        return "Queues";
    }
}
