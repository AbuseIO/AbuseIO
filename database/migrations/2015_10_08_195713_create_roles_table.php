<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
                // Columns
                $table->increments('id');
                $table->string('name', 80);
                $table->string('description');
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
                'id'          => 1,
                'name'        => 'Admin',
                'description' => 'System Administrator',
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
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
