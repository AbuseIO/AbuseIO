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
            $role_found = false;
            $roles = $admin->roles()->get();
            foreach ($roles as $role) {
                if ($role->name == 'Admin') {
                    $role_found = true;
                    break 1;
                }
            }
            if (!$role_found) {
                // Admin doesn't have admin role add it
                $admin->roles()->attach($admin_role);
                echo "Attached admin_role to admin user\n";
            } else {
                echo "Admin already has the admin_role, won't add it again\n";
            }
        } else {
            // no admin of admin role found
            throw new Exception("Couldn't find admin or admin_role, these should exists in this stage");
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
