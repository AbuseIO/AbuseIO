<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Collectors\Factory as CollectorFactory;
use Carbon;

class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'collector:list
                            {--filter= : Applies a filter on the collector (namen) }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available collectors';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [ 'Name', 'Description' ];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = [ ];

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function hydrateCollectorsWithDescription($collectors)
    {
        $objects = [];
        foreach($collectors as $collector) {
            $objects[] =
                [
                    'name'          => $collector,
                    'description'   => config("collectors.{$collector}.collector.description"),
                ];

        }

        return $objects;

    }

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
        $collectors = preg_grep(
            "/$filter/i",
            collectorFactory::getCollectors()
        );

        return $this->hydrateCollectorsWithDescription($collectors);
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        $collectors = collectorFactory::getCollectors();

        return $this->hydrateCollectorsWithDescription($collectors);
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "collector";
    }
}
