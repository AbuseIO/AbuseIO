<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Role;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    /**
     * @var array
     */
    protected $filterArguments = ['name'];

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['ID', 'Name', 'Description', 'Permissions'];

    /**
     * The fields of the table / database row.
     *
     * @var array
     */
    protected $fields = ['id', 'name', 'description'];

    /**
     * {@inheritdoc}.
     */
    private function hydrateRolesWithPermissionCount($roles)
    {
        $rolelist = [];
        foreach ($roles as $role) {
            $permissionCount = $role->permissions()->count();

            $role = $role->toArray();
            $role['permissions'] = $permissionCount;

            $rolelist[] = $role;
        }

        return $rolelist;
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
        $roles = Role::where('name', 'like', "%{$filter}%")->get($this->fields);

        return $this->hydrateRolesWithPermissionCount($roles);
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        $roles = Role::all($this->fields);

        return $this->hydrateRolesWithPermissionCount($roles);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'role';
    }
}
