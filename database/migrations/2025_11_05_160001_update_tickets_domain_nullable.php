<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTicketsDomainNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Allow domain to be nullable to align with Ticket validation and saver
        DB::statement('ALTER TABLE `tickets` MODIFY `domain` VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert domain back to NOT NULL
        DB::statement('ALTER TABLE `tickets` MODIFY `domain` VARCHAR(255) NOT NULL');
    }
}