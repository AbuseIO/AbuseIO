<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'contacts',
            function (Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->integer('account_id')->unsigned();
                $table->string('reference')->unique();
                $table->string('name');
                $table->string('email');
                $table->string('api_host');
                $table->boolean('auto_notify')->unsigned();
                $table->boolean('enabled')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('reference');
                $table->index('name');
                $table->index('email');
                $table->index('api_host');
                $table->index('auto_notify');
                $table->index('account_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
