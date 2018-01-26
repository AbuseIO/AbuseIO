<?php

use Illuminate\Database\Migrations\Migration;

class FixPhishingNaming extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \AbuseIO\Models\Ticket::where('class_id','=','PHISING_WEBSITE')->update(['class_id' => 'PHISHING_WEBSITE']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \AbuseIO\Models\Ticket::where('class_id','=','PHISHING_WEBSITE')->update(['class_id' => 'PHISING_WEBSITE']);
    }
}
