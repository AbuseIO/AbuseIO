<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
                // Columns
                $table->increments('id')->unsigned();
                $table->integer('account_id')->unsigned();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('first_name', 80);
                $table->string('last_name', 80);
                $table->string('locale', 3)->default('en');
                $table->boolean('disabled')->default(false);
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();

                // Indexes
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
