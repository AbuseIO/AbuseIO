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

        //TODO Fix

    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "queue";
    }
}
