<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
                // Columns
                $table->increments('id');
                $table->string('name', 80);
                $table->string('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
                'name'        => 'login_portal',
                'description' => 'Login to portal',
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ],
            [
                'name'        => 'login_api',
                'description' => 'Login to api',
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ],
            [
                'name'        => 'profile_manage',
                'description' => 'Manage own profile',
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ],
        ];

        // Add scripted permissions (controllers)
        $controllers = [
            'contacts',
            'netblocks',
            'domains',
            'tickets',
            'notes',
            'search',
            'analytics',
            'accounts',
            'users',
            'brands',
            'templates',
            'evidence',
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
                    'name'        => "{$controller}_{$action}",
                    'description' => "Allow to {$action} {$controller}",
                    'created_at'  => new DateTime(),
                    'updated_at'  => new DateTime(),
                ];
            }
        }

        // disable / enable for accounts and users
        foreach (['accounts', 'users'] as $controller) {
            foreach (['disable', 'enable'] as $action) {
                $permissions[] = [
                    'name'        => "{$controller}_{$action}",
                    'description' => "Allow to {$action} {$controller}",
                    'created_at'  => new DateTime(),
                    'updated_at'  => new DateTime(),
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
