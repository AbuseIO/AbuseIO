<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractListCommand;
use Carbon;
use Config;
use Queue;

class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'queue:list
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Show all use queues in Beanstalkd';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [ 'Name', 'Urgent', 'Ready', 'Reserved', 'delayed', 'buried', 'total', 'using', 'watching', 'waiting', 'deleted', 'paused' ];

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

    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {

        $pheanstalk = Queue::getPheanstalk();
        $queues = [];

        foreach ($pheanstalk->listTubes() as $queue) {
            if (preg_match('#^abuseio#', $queue) === 1) {
                $queueStatus = $pheanstalk->statsTube($queue);

                $queues[] = [
                    'name'          => substr($queueStatus['name'], 8),
                    'urgent'        => $queueStatus['current-jobs-urgent'],
                    'ready'         => $queueStatus['current-jobs-ready'],
                    'reserved'      => $queueStatus['current-jobs-reserved'],
                    'delayed'       => $queueStatus['current-jobs-delayed'],
                    'buried'        => $queueStatus['current-jobs-buried'],
                    'total'         => $queueStatus['total-jobs'],
                    'using'         => $queueStatus['current-using'],
                    'watching'      => $queueStatus['current-watching'],
                    'waiting'       => $queueStatus['current-waiting'],
                    'deleted'       => $queueStatus['cmd-delete'],
                    'paused'        => $queueStatus['cmd-pause-tube'],


                ];
            }
        }

        return $queues;

    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "queue";
    }
}
