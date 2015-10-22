<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'roles',
            function (Blueprint $table) {

                $table->increments('id');
                $table->string('role_title');
                $table->string('role_slug');

            }
        );

        $this->addDefaultRoles();
    }

    public function addDefaultRoles()
    {

        DB::table('roles')->where('id', '=', '1')->delete();
        $roles = [
            [
                'id'                        => 1,
                'role_title'                => 'System Administrator',
                'role_slug'                 => 'admin',
            ],
            [
                'id'                        => 2,
                'role_title'                => 'Abusedesk User',
                'role_slug'                 => 'abusedesk',
            ],
            [
                'id'                        => 3,
                'role_title'                => 'Servicedesk User',
                'role_slug'                 => 'servicedesk',
            ],
        ];
        DB::table('roles')->insert($roles);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
    }
}
