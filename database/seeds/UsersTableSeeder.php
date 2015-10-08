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
                'id'            => 1,
                'name'          => 'admin',
                'password'      => Hash::make($defaultAdminPassword)
            ],
        ];

        DB::table('users')->insert($users);

        echo PHP_EOL . "Default admin account created with password: $defaultAdminPassword" . PHP_EOL . PHP_EOL;

    }
}
