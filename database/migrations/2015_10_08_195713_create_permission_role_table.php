<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'permission_role',
            function (Blueprint $table) {

                $table->increments('id');
                $table->integer('permission_id');
                $table->integer('role_id');

            }
        );

        $this->addDefaultPermissionRole();
    }

    public function addDefaultPermissionRole()
    {
        // Always recreate the permissions for the system administrator
        DB::table('permission_role')->where('role_id', '=', '1')->delete();
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
