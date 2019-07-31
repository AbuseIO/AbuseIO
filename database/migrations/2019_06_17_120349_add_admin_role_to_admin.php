<?php

use AbuseIO\Models\Role;
use AbuseIO\Models\User;
use Illuminate\Database\Migrations\Migration;

class AddAdminRoleToAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @throws Exception
     *
     * @return void
     */
    public function up()
    {
        $admin = User::find(1);
        $admin_role = Role::find(1);
        if ($admin && $admin_role) {
            $admin->roles()->syncWithoutDetaching([$admin_role->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
