<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Role;
use AbuseIO\Models\RoleUser;
use AbuseIO\Models\User;
use Illuminate\Console\Command;

/**
 * Class RevokeCommand.
 */
class RevokeCommand extends Command
{
    use ExitCodeHooks;
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
     * @return int
     */
    public function handle(): int
    {
        if (empty($this->option('role')) &&
            empty($this->option('user'))
        ) {
            $this->error('Missing options for role and/or user(e-mail) to select');

            return $this->getInvalidOptionExitCode();
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

                return $this->getNotFoundExitCode();
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

                return $this->getNotFoundExitCode();
            }
        }

        $roleUser = RoleUser::all()
            ->where('role_id', $role->id)
            ->where('user_id', $user->id)
            ->first();

        if (!is_object($roleUser)) {
            $this->error(
                'Nothing to delete, this {$role->name} role is not linked to the user {$user->email}'
            );

            return $this->getNotFoundExitCode();
        }

        if (!$roleUser->delete()) {
            $this->error('Failed to remove the role from the database');

            return $this->getDeleteFailedExitCode();
        }

        $this->info("The role {$role->name} has been revoked from user {$user->email}");

        return $this->getSuccessExitCode();
    }
}
