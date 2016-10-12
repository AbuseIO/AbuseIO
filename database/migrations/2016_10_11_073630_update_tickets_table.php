<?php

use AbuseIO\Models\Ticket;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create the ash_token fields
        Schema::table('tickets', function ($table) {
            $table->string('ash_token_ip');
            $table->string('ash_token_domain');
        });

        $this->_updateTokens();
    }

    /**
     * update all existing tickets with the ashtokens
     * These are automatically added when the ticket is
     * saved.
     */
    private function _updateTokens()
    {
        $tickets = Ticket::all();

        foreach ($tickets as $ticket) {
            $ticket->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
