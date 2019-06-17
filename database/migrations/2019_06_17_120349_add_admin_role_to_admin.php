<?php

use AbuseIO\Models\User;
use AbuseIO\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddAdminRoleToAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
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
                $this->info("Attached admin_role to admin user");
            } else {
                $this->info("Admin already has the admin_role, won't add it again");
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
