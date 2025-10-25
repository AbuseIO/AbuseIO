<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTicketsApiHostNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Allow ip_contact_api_host and domain_contact_api_host to be nullable
        DB::statement("ALTER TABLE `tickets` MODIFY `ip_contact_api_host` VARCHAR(255) NULL");
        DB::statement("ALTER TABLE `tickets` MODIFY `domain_contact_api_host` VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert columns to NOT NULL
        DB::statement("ALTER TABLE `tickets` MODIFY `ip_contact_api_host` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `tickets` MODIFY `domain_contact_api_host` VARCHAR(255) NOT NULL");
    }
}