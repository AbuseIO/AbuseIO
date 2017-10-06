<?php

use AbuseIO\Models\Ticket;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketsTableAddApitoken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function ($table) {
            $table->string('api_token', 32)->nullable();
        });

        $this->_updateTokens();
    }

    /**
     * update all existing tickets with the apiToken and ash_tokens
     * These are automatically added when the ticket is
     * saved.
     *
     * @return void
     */
    private function _updateTokens()
    {
        Ticket::all()->map(function ($ticket) {
            $ticket->save();
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
            $table->dropColumn('api_token');
        });
    }
}
