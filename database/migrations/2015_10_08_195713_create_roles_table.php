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
                $table->timestamps();
                $table->softDeletes();

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
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime,
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
