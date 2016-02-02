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

        // Add the example users
        $adminPassword  = (app()->environment() == 'development') ? 'admin' : substr(md5(rand()), 0, 8);
        $userPassword   = (app()->environment() == 'development') ? 'user' : substr(md5(rand()), 0, 8);

        $users = [
            [
                'id'                        => 1,
                'email'                     => 'admin@isp.local',
                'first_name'                => 'System',
                'last_name'                 => 'Admin',
                'password'                  => Hash::make($adminPassword),
                'account_id'                => 1,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 2,
                'email'                     => 'user@isp.local',
                'first_name'                => 'Elizabeth',
                'last_name'                 => 'Smith',
                'password'                  => Hash::make($userPassword),
                'account_id'                => 1,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 3,
                'email'                     => 'admin@isp2.local',
                'first_name'                => 'Warren',
                'last_name'                 => 'King',
                'password'                  => Hash::make($adminPassword),
                'account_id'                => 2,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 4,
                'email'                     => 'user@isp2.local',
                'first_name'                => 'Sophie',
                'last_name'                 => 'Davidson',
                'password'                  => Hash::make($userPassword),
                'account_id'                => 2,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 5,
                'email'                     => 'admin@isp3.local',
                'first_name'                => 'Richard',
                'last_name'                 => 'Paterson',
                'password'                  => Hash::make($adminPassword),
                'account_id'                => 3,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],

        ];
        DB::table('users')->insert($users);

        // New role for the user
        $roles =  [
            [
                'id'                        => 2,
                'name'                      => 'Abuse',
                'description'               => 'Abusedesk User',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
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
            'evidence_view',
        ];

        // User role permissions
        foreach ($permissions as $permission_name) {
            $permission = Permission::where('name', '=', $permission_name)->first();

            $permission_role[] = [
                'permission_id'             => $permission->id,
                'role_id'                   => '2',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ];
        }

        DB::table('permission_role')->insert($permission_role);


        // Give the admin user the default role as system administrator (1) and the user the user role (2)
        DB::table('role_user')->delete();
        $role_user = [
            [
                'id'                        => 1,
                'role_id'                   => 1,   // Admin user role
                'user_id'                   => 1,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 2,
                'role_id'                   => 2,   // Abusedesk user role
                'user_id'                   => 2,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 3,
                'role_id'                   => 1,   // Admin user role
                'user_id'                   => 3,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 4,
                'role_id'                   => 2,   // Abusedesk user role
                'user_id'                   => 4,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
            [
                'id'                        => 5,
                'role_id'                   => 1,   // Admin user role
                'user_id'                   => 5,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
            ],
        ];
        DB::table('role_user')->insert($role_user);

        // Show the password in CLI that was generated when seeding the test admin user
        echo "\nDefault admin user '{$users[0]['email']}' created with password: '{$adminPassword}'";
        // Show the password in CLI that was generated when seeding the test abusedesk user
        echo "\nDefault user '{$users[1]['email']}' created with password: '{$userPassword}'";
        // Show the password in CLI that was generated when seeding the test second account admin user
        echo "\nSecond admin user '{$users[2]['email']}' created with password: '{$adminPassword}'";
        // Show the password in CLI that was generated when seeding the test second account abusedesk user
        echo "\nSecond user '{$users[3]['email']}' created with password: '{$userPassword}'";
        // Show the password in CLI that was generated when seeding the test second account admin user
        echo "\nThird admin user '{$users[4]['email']}' created with password: '{$adminPassword}'\n\n";
    }
}
