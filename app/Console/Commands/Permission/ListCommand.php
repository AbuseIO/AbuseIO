<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Permission;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    /**
     * @var array
     */
    protected $filterArguments = ['id'];

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['Id', 'Name', 'Description'];

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $permission  \AbuseIO\Models\Permission|null */
        foreach ($list as $permission) {
            $result[] = [
                $permission->id,
                $permission->name,
                $permission->description,
            ];
        }

        return $result;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        return Permission::where('id', $filter)
            ->orWhere('name', 'like', '%'.$filter.'%')
            ->get();
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return Permission::all();
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'permission';
    }
}
