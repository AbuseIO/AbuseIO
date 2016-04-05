<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Collectors\Factory as CollectorFactory;
use AbuseIO\Console\Commands\AbstractListCommand;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    protected $filterArguments = ['name'];

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['Name', 'Description'];

    /**
     * The fields of the table / database row.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Execute the console command.
     *
     * @param array $collectors
     *
     * @return bool
     */
    public function hydrateCollectorsWithDescription($collectors)
    {
        $objects = [];
        foreach ($collectors as $collector) {
            $objects[] =
                [
                    'name'        => $collector,
                    'description' => config("collectors.{$collector}.collector.description"),
                ];
        }

        return $objects;
    }

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
        return $list;
    }

    /**
     * {@inheritdoc}.
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
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        $collectors = collectorFactory::getCollectors();

        return $this->hydrateCollectorsWithDescription($collectors);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'collector';
    }
}
