<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketGraphPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_graph_points', function (Blueprint $table) {
            $table->increments('id');
            $table->date('day_date');
            $table->string('class');
            $table->string('type');
            $table->string('status');
            $table->integer('count');
            $table->string('lifecycle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_graph_points');
    }
}
