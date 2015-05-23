<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetblocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('netblocks', function(Blueprint $table)
		{
            $table->integer('id');
            //$table->string('first_ip');
            //$table->string('last_ip');
            $table->integer('contact_id');
            $table->boolean('enabled');
			$table->timestamps();

            //$table->index('first_ip');
            //$table->index('last_ip');
            $table->index('contact_id');
            $table->index('enabled');

            //$table->unique('first_ip', 'last_ip');
		});

        DB::statement('ALTER TABLE `netblocks` ADD `first_ip` VARBINARY(16)');
        DB::statement('ALTER TABLE `netblocks` ADD `last_ip`  VARBINARY(16)');
        DB::statement('ALTER TABLE `netblocks` ADD UNIQUE( `first_ip`, `last_ip`)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement('ALTER TABLE netblocks DROP INDEX first_ip');
        DB::statement('ALTER TABLE `netblocks` DROP COLUMN `first_ip`');
        DB::statement('ALTER TABLE `netblocks` DROP COLUMN `last_ip`');

		Schema::drop('netblocks');
	}

}
