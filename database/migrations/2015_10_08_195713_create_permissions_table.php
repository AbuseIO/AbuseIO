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
                'permission_title'          => 'login using portal',
                'permission_slug'           => 'login_portal',
                'permission_description'    => 'Login to portal',
            ],
            [
                'permission_title'          => 'login using api',
                'permission_slug'           => 'login_api',
                'permission_description'    => 'Login to api',
            ],
            [
                'permission_title'          => 'manage profile',
                'permission_slug'           => 'profile_manage',
                'permission_description'    => 'Manage own profile',
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
                    'permission_slug'           => "{$controller}_{$action}",
                    'permission_description'    => "Allow to {$action} {$controller}",
                ];
            }
        }

        // disable / enable for accounts and users
        foreach (['accounts', 'users'] as $controller) {
            foreach (['disable', 'enable'] as $action) {
                $permissions[] = [
                    'permission_title'          => "{$action} {$controller}",
                    'permission_slug'           => "{$controller}_{$action}",
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
