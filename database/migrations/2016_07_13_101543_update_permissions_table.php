<?php

use Illuminate\Database\Migrations\Migration;

class UpdatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add new permissions for the incident model

        $actions = [
            'view',
            'create',
            'edit',
            'delete',
            'export',
        ];

        $controller = 'incidents';
        foreach ($actions as $action) {
            $permissions[] = [
                'name'        => "{$controller}_{$action}",
                'description' => "Allow to {$action} {$controller}",
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ];
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
        // do nothing
    }
}
