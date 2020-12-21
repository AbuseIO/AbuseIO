<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Models\Role;
use AbuseIO\Models\RoleUser;
use AbuseIO\Models\User;
use Illuminate\Console\Command;

/**
 * Class RevokeCommand.
 */
class RevokeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'role:revoke
                            {--role= : The role name or ID where the permission will be revoked from }
                            {--user= : The user name(e-mail) or ID of which role the permission will be revoked from }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes a role from a user';

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
        if (empty($this->option('role')) &&
            empty($this->option('user'))
        ) {
            $this->error('Missing options for role and/or user(e-mail) to select');

            return false;
        }

        /*
         * Detect the role->id and lookup the user if its thru a user assignment.
         */
        $role = false;
        $user = false;

        if (!empty($this->option('role'))) {
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
        }

        if (!empty($this->option('user'))) {
            if (!is_object($user)) {
                $user = User::where('email', $this->option('user'))->first();
            }

            if (!is_object($user)) {
                $user = User::find($this->option('user'));
            }

            if (!is_object($user)) {
                $this->error('Unable to find user with this criteria');

                return false;
            }
        }

        $roleUser = RoleUser::all()
            ->where('role_id', $role->id)
            ->where('user_id', $user->id)
            ->first();

        if (!is_object($roleUser)) {
            $this->error(
                'Nothing to delete, this {$permission->name} permission is not linked to the role {$role->name}'
            );

            return false;
        }

        if (!$roleUser->delete()) {
            $this->error('Failed to remove the permission into the database');

            return false;
        }

        $this->info("The role {$role->name} has been revoked from user {$user->email}");

        return true;
    }
}
