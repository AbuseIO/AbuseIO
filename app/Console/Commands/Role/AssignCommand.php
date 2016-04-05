<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Models\Role;
use AbuseIO\Models\RoleUser;
use AbuseIO\Models\User;
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
    protected $signature = 'role:assign
                            {--role= : The role name or ID where the permission will be assigned to }
                            {--user= : The user name(e-mail) or ID of which role the permission will be assigned to }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a users';

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
         * Detect the role->id and lookup the user if its through a user assignment.
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
        }

        if (!empty($this->option('user'))) {
            if (!is_object($user)) {
                $user = User::where('email', $this->option('user'))->first();
            }

            if (!is_object($user)) {
                $user = Role::find($this->option('user'));
            }
        }

        if (!is_object($role) || !is_object($user)) {
            $this->error('Unable to find role with this criteria');

            return false;
        }

        $RoleUser = new RoleUser();

        $RoleUser->user_id = $user->id;
        $RoleUser->role_id = $role->id;

        $validation = Validator::make($RoleUser->toArray(), RoleUser::createRules($RoleUser));

        if ($validation->fails()) {
            $this->warn('The role has already been granted this permission');

            $this->error('Failed to create the permission due to validation warnings');

            return false;
        }

        if (!$RoleUser->save()) {
            $this->error('Failed to save the permission into the database');

            return false;
        }

        $this->info("The role {$role->name} has been granted to user {$user->email}");

        return true;
    }
}
