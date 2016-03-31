<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
                // Columns
                $table->increments('id');
                $table->integer('role_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('role_id');
                $table->index('user_id');

                // Uniques
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
