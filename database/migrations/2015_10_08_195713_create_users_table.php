<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {

                $table->increments('id')->unsigned();
                $table->integer('account_id')->unsigned();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('first_name');
                $table->string('last_name');
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();

                $table->index('account_id');
                $table->index('email');
                $table->index('first_name');
                $table->index('last_name');
            }
        );

        $this->addDefaultUsers();
    }

    public function addDefaultUsers()
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
        Schema::drop('users');
    }
}
