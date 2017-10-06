<?php

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
