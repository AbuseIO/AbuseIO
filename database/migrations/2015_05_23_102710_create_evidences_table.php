<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvidencesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'evidences',
            function (Blueprint $table) {

                $table->increments('id');
                $table->string('filename')->unique();
                $table->string('sender');
                $table->string('subject');
                $table->timestamps();
    
                $table->index('filename');
                $table->index('sender');
                $table->index('subject');

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
        Schema::drop('evidences');
    }
}
