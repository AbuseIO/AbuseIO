<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Role;

class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:list
                            {--filter : Applies a filter on the role name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available roles';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['ID', 'Name', 'Description', 'Permissions'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['id', 'name', 'description'];


    /**
     * @param $roles
     * @return array
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
        $roles = Role::where('name', 'like', "%{$filter}%")->get($this->fields);
        return $this->hydrateRolesWithPermissionCount($roles);
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        $roles = Role::all($this->fields);
        return $this->hydrateRolesWithPermissionCount($roles);
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "role";
    }
}
