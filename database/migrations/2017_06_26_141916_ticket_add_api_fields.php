<?php

use Illuminate\Database\Migrations\Migration;

class TicketAddApiFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function ($table) {
            $table->string('remote_api_url')->nullable();
            $table->integer('remote_ticket_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function ($table) {
            $table->dropColumn('remote_api_url');
            $table->dropColumn('remote_ticket_id');
        });
    }
}
