<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Permission;
use AbuseIO\Models\PermissionRole;
use AbuseIO\Models\Role;
use AbuseIO\Models\User;
use Illuminate\Console\Command;

/**
 * Class RevokeCommand.
 */
class RevokeCommand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'permission:revoke
                            {--permission= : The permission name or ID to revoke }
                            {--role= : The role name or ID where the permission will be revoked from }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes a permission from a role';

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
            throw new \RuntimeException('Missing options for role and/or permission to select');

            return false;
        }

        /*
         * Detect the role->id and lookup the user if its thru a user assignment.
         */
        $role = false;

        if (!empty($this->option('role'))) {
            if (!is_object($role)) {
                $role = Role::where('name', $this->option('role'))->first();
            }

            if (!is_object($role)) {
                $role = Role::find($this->option('role'));
            }
        }

        if (!empty($this->option('user'))) {
            if (!is_object($role)) {
                $user = User::where('email', $this->option('user'))->first();
                if (is_object($user)) {
                    $role = $user->roles->first();
                }
            }

            if (!is_object($role)) {
                $user = Role::find($this->option('user'));
                if (is_object($user)) {
                    $role = $user->roles->first();
                }
            }
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

        $permissionRole = PermissionRole::all()
            ->where('role_id', $role->id)
            ->where('permission_id', $permission->id)
            ->first();

        if (!is_object($permissionRole)) {
            $this->error(
                'Nothing to delete, this {$permission->name} permission is not linked to the role {$role->name}'
            );

            return false;
        }

        if (!$permissionRole->delete()) {
            $this->error('Failed to remove the permission into the database');

            return false;
        }

        $this->info("The permission {$permission->name} has been revoked from role {$role->name}");

        return true;
    }
}
