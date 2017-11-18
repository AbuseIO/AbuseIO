<?php

use AbuseIO\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations. This migration has a later timestamp as it depends on permissions table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'permission_role',
            function (Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->integer('permission_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('permission_id');
                $table->index('role_id');

                // Uniques
                $table->unique(['permission_id', 'role_id']);
            }
        );

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
        Schema::drop('permission_role');
    }
}
