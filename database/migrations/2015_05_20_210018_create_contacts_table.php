<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('rpc_host');
            $table->string('rpc_key');
            $table->boolean('auto_notify');
            $table->boolean('enabled');
			$table->timestamps();

            $table->index('reference');
            $table->index('name');
            $table->index('email');
            $table->index('rpc_host');
            $table->index('auto_notify');
            $table->index('enabled');

		});
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
