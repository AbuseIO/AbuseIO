<?php

use AbuseIO\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class UpdatePermissionsRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addDefaultPermissionRole();
    }

    public function addDefaultPermissionRole()
    {
        // Always recreate the permissions for the system administrator
        DB::table('permission_role')->where('role_id', '=', '1')->delete();

        // Add all permissions to the default system administrator role (1)
        $permission_role = [];
        foreach (Permission::all() as $permission) {
            $permission_role[] = [
                'permission_id' => $permission->id,
                'role_id'       => '1',
                'created_at'    => new DateTime(),
                'updated_at'    => new DateTime(),
            ];
        }

        DB::table('permission_role')->insert($permission_role);
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
