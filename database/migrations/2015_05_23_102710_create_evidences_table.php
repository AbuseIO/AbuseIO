<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
                // Columns
                $table->increments('id');
                $table->string('filename')->unique();
                $table->string('sender');
                $table->string('subject');
                $table->timestamps();
                $table->softDeletes();

                // Indexes
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
