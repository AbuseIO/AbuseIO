<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'notes',
            function (Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->integer('ticket_id')->unsigned();
                $table->string('submitter', 80);
                $table->longText('text');
                $table->boolean('hidden')->default(false);
                $table->boolean('viewed')->default(false);
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('ticket_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notes');
    }
}
