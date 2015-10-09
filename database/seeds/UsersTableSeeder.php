<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        /*
         * Todo: move this into better place as its not really seeding
         */
        DB::table('users')->delete();

        $defaultAdminPassword = substr(md5(rand()), 0, 8);

        $users = [
            [
                'id'                        => 1,
                'email'                     => 'admin@isp.local',
                'first_name'                => 'admin',
                'last_name'                 => 'isp.local',
                'password'                  => Hash::make($defaultAdminPassword)
            ],
        ];

        DB::table('users')->insert($users);

        echo PHP_EOL . "Default admin account 'admin@isp.local' created with password: '$defaultAdminPassword'" .
            PHP_EOL . PHP_EOL;



        DB::table('role_user')->delete();
        $role_user = [
            [
                'id'                        => 1,
                'role_id'                   => 1,
                'user_id'                   => 1,
            ],
        ];
        DB::table('role_user')->insert($role_user);



        DB::table('permissions')->delete();
        $permissions = [
            [
                'id'                        => 1,
                'permission_title'          => 'admin.login',
                'permission_slug'           => 'admin.login',
                'permission_description'    => 'Login to admin portal',
            ],
            [
                'id'                        => 2,
                'permission_title'          => 'admin.view.contacts',
                'permission_slug'           => 'admin.view.contacts',
                'permission_description'    => 'Allow to view contacts',
            ],
        ];
        DB::table('permissions')->insert($permissions);



        DB::table('permission_role')->delete();
        $permission_role = [
            [
                'id'                        => '1',
                'permission_id'             => '1',
                'role_id'                   => '1',
            ],
            [
                'id'                        => '2',
                'permission_id'             => '2',
                'role_id'                   => '1',
            ],
        ];
        DB::table('permission_role')->insert($permission_role);



        DB::table('roles')->delete();
        $roles = [
            [
                'id'                        => 1,
                'role_title'                => 'System Administrator',
                'role_slug'                 => 'admin',
            ],
        ];
        DB::table('roles')->insert($roles);

    }
}
