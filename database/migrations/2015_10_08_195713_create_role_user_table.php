<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'role_user',
            function (Blueprint $table) {

                $table->increments('id');
                $table->integer('role_id');
                $table->integer('user_id');
                $table->timestamps();
                $table->softDeletes();

                $table->index('role_id');
                $table->index('user_id');

                $table->unique(['role_id', 'user_id']);
            }
        );

        $this->addDefaultRolesUsers();
    }

    public function addDefaultRolesUsers()
    {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
    }
}
