<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Models\Permission;
use AbuseIO\Models\PermissionRole;
use AbuseIO\Models\Role;
use Illuminate\Console\Command;
use Validator;

/**
 * Class AssignCommand.
 */
class AssignCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'permission:assign
                            {--permission= : The permission name or ID to assign }
                            {--role= : The role name or ID where the permission will be assigned to }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a permission to a role ';

    /**
     * {@inheritdoc}.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        if (empty($this->option('role')) ||
            empty($this->option('permission'))
        ) {
            $this->error('Missing options for role and/or permission to select, try --help');

            return false;
        }

        /*
         * Detect the role->id and lookup the user if its thru a user assignment.
         */
        $role = false;

        if (!is_object($role)) {
            $role = Role::where('name', $this->option('role'))->first();
        }

        if (!is_object($role)) {
            $role = Role::find($this->option('role'));
        }

        if (!is_object($role)) {
            $this->error('Unable to find role with this criteria');

            return false;
        }

        /*
         * Detect the permission->id and lookup the name if needed
         */
        $permission = false;
        if (!is_object($permission)) {
            $permission = Permission::where('name', $this->option('permission'))->first();
        }

        if (!is_object($permission)) {
            $permission = Permission::find($this->option('permission'));
        }

        if (!is_object($permission)) {
            $this->error('Unable to find permission with this criteria');

            return false;
        }

        $permissionRole = new PermissionRole();

        $permissionRole->permission()->associate($permission);
        $permissionRole->role()->associate($role);

        $validation = Validator::make($permissionRole->toArray(), PermissionRole::createRules($permissionRole));

        if ($validation->fails()) {
            $this->warn('The role has already been granted this permission');

            $this->error('Failed to create the permission due to validation warnings');

            return false;
        }

        if (!$permissionRole->save()) {
            $this->error('Failed to save the permission into the database');

            return false;
        }

        $this->info("The permission {$permission->name} has been granted to role {$role->name}");

        return true;
    }
}
