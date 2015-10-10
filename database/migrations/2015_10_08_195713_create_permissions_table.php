<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'permissions',
            function (Blueprint $table) {

                $table->increments('id');
                $table->string('permission_title');
                $table->string('permission_slug');
                $table->string('permission_description')->nullable();

            }
        );

        $this->addDefaultPermissions();
    }

    public function addDefaultPermissions()
    {
        /*
         * We delete all the permissions and recreate them as these are from within the hardcode ACL options which
         * users cannot modify unless changing the code. If they can change the code they can change the defaults
         * here as well.
         */
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

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permissions');
    }
}
