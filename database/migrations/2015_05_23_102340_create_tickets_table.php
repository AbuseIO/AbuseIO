<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('ip');
            $table->string('domain');
            $table->timestamp('first_seen');
            $table->timestamp('last_seen');
            $table->integer('class_id');
            $table->integer('type_id');
            $table->string('ip_contact_reference');
            $table->string('ip_contact_name');
            $table->string('ip_contact_email');
            $table->string('ip_contact_rpchost');
            $table->string('ip_contact_rpckey');
            $table->string('domain_contact_reference');
            $table->string('domain_contact_name');
            $table->string('domain_contact_email');
            $table->string('domain_contact_rpchost');
            $table->string('domain_contact_rpckey');
            $table->integer('status_id');
            $table->boolean('auto_notify');
            $table->integer('notified_count');
            $table->integer('report_count');
            $table->integer('last_notify_count');
            $table->timestamp('last_notify_timestamp');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tickets');
	}

}
