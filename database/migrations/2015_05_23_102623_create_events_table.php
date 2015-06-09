<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('evidence_id')->unsigned();
            $table->string('source');
            $table->timestamp('timestamp');
            $table->longText('information');
			$table->timestamps();

            $table->index('ticket_id');
            $table->index('evidence_id');
            $table->index('source');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
