<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractShowCommand;
use Carbon;
use Config;
use Queue;

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
    protected $description = 'Show all pending jobs from a specific Beanstalkd queue';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [ 'ID', 'Worker' ];

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
        return $list;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        $queue = ($filter) ? "abuseio_". $filter : Config::get('queue.connections.beanstalkd.queue');

        $pheanstalk = Queue::getPheanstalk();
        $pheanstalk->useTube($queue);
        $pheanstalk->watch($queue);

        $jobs = [];
        while ($job = $pheanstalk->reserve(0)) {
            $jobData = json_decode($job->getData());
            $jobCommand = unserialize($jobData->data->command);

            $jobs[] = [
                'id'        =>  $job->getId(),
                'worker'    => class_basename($jobCommand),
            ];
        }

        return $jobs;
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
