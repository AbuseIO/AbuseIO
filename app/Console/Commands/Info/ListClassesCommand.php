<?php

namespace AbuseIO\Console\Commands\Info;

use AbuseIO\Console\Commands\AbstractListCommand;

/**
 * Class ListClassesCommand
 * @package AbuseIO\Console\Commands\Info
 */
class ListClassesCommand extends AbstractListCommand
{
    /**
     * @var array
     */
    protected $filterArguments = [ ];

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Tag', 'Name'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['Tag', 'Name'];


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
        $taglist = [];
        foreach (trans('classifications') as $tag => $classInfo) {
            if (preg_match("/{$filter}/i", $tag) ||
                preg_match("/{$filter}/i", $classInfo['name'])
            ) {
                $taglist[$tag] =
                    [
                        $tag,
                        $classInfo['name']
                    ];
            }
        }
        return $taglist;
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        $taglist = [];
        foreach (trans('classifications') as $tag => $classInfo) {
            $taglist[$tag] =
                [
                    $tag,
                    $classInfo['name']
                ];
        }
        return $taglist;
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "info";
    }

    /**
     * Default subcommand name
     *
     * @return string
     */
    public function setCommandName()
    {
        return 'listclasses';
    }
}
