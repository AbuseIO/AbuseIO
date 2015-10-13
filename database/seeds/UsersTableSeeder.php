<?php

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

        $users = [
            [
                'id'                        => 1,
                'email'                     => $defaultAdminUsername,
                'first_name'                => 'default',
                'last_name'                 => 'admin',
                'password'                  => Hash::make($defaultAdminPassword),
                'account_id'                => 1
            ],
        ];
        DB::table('users')->insert($users);

        // Give the admin user the default role as system administrator (1)
        DB::table('role_user')->delete();
        $role_user = [
            [
                'id'                        => 1,
                'role_id'                   => 1,
                'user_id'                   => 1,
            ],
        ];
        DB::table('role_user')->insert($role_user);

        // Show the password in CLI that was generated when seeding the test admin user
        echo PHP_EOL ."Default admin user '{$defaultAdminUsername}' created with password: '{$defaultAdminPassword}'" .
            PHP_EOL . PHP_EOL;

    }
}
