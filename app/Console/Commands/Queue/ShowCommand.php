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

        //TODO Fix
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
