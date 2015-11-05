<?php

use AbuseIO\Models\Permission;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        /*
         * This creates the default users for testing with a default password. Production installs should call the
         * CLI tool to use user:create, user:delete, user:addrole, user:deleterole to gain access to the system.
         */
        DB::table('users')->delete();

        // Add the admin user
        $defaultAdminUsername = 'admin@isp.local';
        $defaultAdminPassword = substr(md5(rand()), 0, 8);
        $defaultUserUsername  = 'user@isp.local';
        $defaultUserPassword = substr(md5(rand()), 0, 8);

        $users = [
            [
                'id'                        => 1,
                'email'                     => $defaultAdminUsername,
                'first_name'                => 'Default',
                'last_name'                 => 'Admin',
                'password'                  => Hash::make($defaultAdminPassword),
                'account_id'                => 1
            ],
            [
                'id'                        => 2,
                'email'                     => $defaultUserUsername,
                'first_name'                => 'Isp',
                'last_name'                 => 'User',
                'password'                  => Hash::make($defaultUserPassword),
                'account_id'                => 1
            ],

        ];
        DB::table('users')->insert($users);

        // New role for the user
        $roles =  [
            [
                'id'                        => 2,
                'role_title'                => 'Abuse User',
                'role_slug'                 => 'abuse',
            ],
        ];

        DB::table('roles')->insert($roles);


        // Permissions for the User role
        $permissions = [
            'login_portal',
            'netblocks_view', 'netblocks_create', 'netblocks_edit', 'netblocks_delete', 'netblocks_export',
            'domains_view', 'domains_create', 'domains_edit', 'domains_delete', 'domains_export',
            'tickets_view', 'tickets_create', 'tickets_edit', 'tickets_delete', 'tickets_export',
            'search_view', 'search_create', 'search_edit', 'search_delete', 'search_export',
            'analytics_view', 'analytics_create', 'analytics_edit', 'analytics_delete', 'analytics_export',
            'accounts_view', 'accounts_create', 'accounts_edit', 'accounts_delete', 'accounts_export',
            'profile_manage',
            'users_view', 'users_edit',
        ];

        // abuseio User role permissions
        foreach ($permissions as $permission_name) {

            $permission = Permission::where('permission_slug','=', $permission_name)->first();

            $permission_role[] = [
                'permission_id'             => $permission->id,
                'role_id'                   => '2',
            ];
        }

        DB::table('permission_role')->insert($permission_role);


        // Give the admin user the default role as system administrator (1) and the user the user role (2)
        DB::table('role_user')->delete();
        $role_user = [
            [
                'id'                        => 1,
                'role_id'                   => 1,
                'user_id'                   => 1,
            ],
            [
                'id'                        => 2,
                'role_id'                   => 2,
                'user_id'                   => 2,
            ],
        ];
        DB::table('role_user')->insert($role_user);

        // Show the password in CLI that was generated when seeding the test admin user
        echo PHP_EOL ."Default admin user '{$defaultAdminUsername}' created with password: '{$defaultAdminPassword}'" .
            PHP_EOL . PHP_EOL;
        // Show the password in CLI that was generated when seeding the test user
        echo PHP_EOL ."Default user '{$defaultUserUsername}' created with password: '{$defaultUserPassword}'" .
            PHP_EOL . PHP_EOL;

    }
}
