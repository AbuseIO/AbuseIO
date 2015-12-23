<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Collectors\Factory as CollectorFactory;
use AbuseIO\Console\Commands\AbstractShowCommand;

class ShowCommand extends AbstractShowCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'collector:show {name}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of an collector';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = [ 'Collector' ];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = [ 'Collector' ];

    /**
     * Execute the console command.
     *
     * @param array $collectors
     * @return boolean
     */
    public function hydrateCollectorWithFields(array $collectors)
    {
        $objects = [];

        foreach($collectors as $field => $value) {
            if(is_bool($value)) {
                $value = castBoolToString($value);
            }
            if(empty($value)) {
                $value = 'Unconfigured';
            }
            if(is_array($value)) {
                $value = implode(PHP_EOL, $value);
            }
            $objects[] =
                [
                    ucfirst($field),
                    $value,
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
        $collector = ucfirst($filter);
        $collector = config("collectors.{$collector}.collector");

        return $this->hydrateCollectorWithFields($collector);
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return [ ];
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "collector";
    }
}
