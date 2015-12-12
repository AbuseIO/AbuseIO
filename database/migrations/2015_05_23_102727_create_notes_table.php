<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

                $table->increments('id');
                $table->integer('ticket_id')->unsigned();
                $table->string('submitter', 80);
                $table->longText('text');
                $table->boolean('hidden')->default(false);
                $table->boolean('viewed')->default(false);
                $table->timestamps();
                $table->softDeletes();

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
