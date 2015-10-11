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

        // Add single permissions
        $permissions = [
            [
                'permission_title'          => 'login admin',
                'permission_slug'           => 'admin_login',
                'permission_description'    => 'Login to admin portal',
            ],
        ];

        // Add scripted permissions (controllers)
        $controllers = [
            'contacts',
            'netblocks',
            'domains',
            'tickets',
            'search',
            'analytics',
            'accounts',
            'users',
            'brands',
            'templates',
        ];
        $actions = [
            'view',
            'create',
            'edit',
            'delete',
            'export',
        ];

        foreach ($controllers as $controller) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'permission_title'          => "{$action} {$controller}",
                    'permission_slug'           => "admin_{$controller}_{$action}",
                    'permission_description'    => "Allow to {$action} {$controller}",
                ];
            }
        }

        // Write permissions into database
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
