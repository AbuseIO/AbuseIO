<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Console\Commands\AbstractShowCommand;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand.
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}.
     */
    protected function getAsNoun()
    {
        return 'collector';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['name', 'description', 'enabled', 'location', 'key'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return ['name'];
    }

    /**
     * {@inheritdoc}.
     */
    public function hydrateCollectorWithFields(array $collectors)
    {
        $objects = [];

        foreach ($collectors as $field => $value) {
            if (is_bool($value)) {
                $value = castBoolToString($value);
            }
            if (empty($value)) {
                $value = 'Unconfigured';
            }
            if (is_array($value)) {
                $value = implode(PHP_EOL, $value);
            }
            $objects[]
                = [
                    ucfirst($field),
                    $value,
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
        $collector = ucfirst($filter);

        $result = config("collectors.{$collector}.collector");

        if (null !== $result) {
            return collect(
                [(object) $result]
            );
        }

        return collect(null);
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return [];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCollectionWithArguments()
    {
        return $this->findWithCondition($this->argument('collector'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'collector',
                InputArgument::REQUIRED,
                'Use the name of a collector to show it.'
            ),
        ];
    }

    /**
     * {@inheritdoc}.
     */
    protected function transformObjectToTableBody($model)
    {
        return [
            ['Name', $model->name],
            ['Description', $model->description],
            ['Enabled', $model->enabled],
            ['Location', $model->location],
            ['Key', $model->key],
        ];
    }
}
