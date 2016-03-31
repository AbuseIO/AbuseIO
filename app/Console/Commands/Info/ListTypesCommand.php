<?php

namespace AbuseIO\Console\Commands\Info;

use AbuseIO\Console\Commands\AbstractListCommand;

/**
 * Class ListTypesCommand.
 */
class ListTypesCommand extends AbstractListCommand
{
    /**
     * @var array
     */
    protected $filterArguments = [];

    /**
     * Override to the command description field.
     *
     * @var string
     */
    protected $commandDescription = 'Shows a list of available types';

    /**
     * Override to the command name field.
     *
     * @var string
     */
    protected $commandName = 'listtypes';

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['Tag', 'Name'];

    /**
     * The fields of the table / database row.
     *
     * @var array
     */
    protected $fields = ['Tag', 'Name'];

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
        $taglist = [];
        foreach (trans('types.type') as $tag => $typeInfo) {
            if (preg_match("/{$filter}/i", $tag) ||
                preg_match("/{$filter}/i", $typeInfo['name'])
            ) {
                $taglist[$tag] =
                    [
                        $tag,
                        $typeInfo['name'],
                    ];
            }
        }

        return $taglist;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        $taglist = [];
        foreach (trans('types.type') as $tag => $typeInfo) {
            $taglist[$tag] =
                [
                    $tag,
                    $typeInfo['name'],
                ];
        }

        return $taglist;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'info';
    }
}
