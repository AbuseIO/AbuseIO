<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetblocksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'netblocks',
            function (Blueprint $table) {

                $table->increments('id');
                $table->integer('contact_id')->unsigned();
                $table->decimal('first_ip', 38, 0);
                $table->decimal('last_ip', 38, 0);
                $table->string('description');
                $table->boolean('enabled')->unsigned();
                $table->timestamps();
    
                $table->index('contact_id');
                $table->index('enabled');
                $table->index('first_ip');
                $table->index('last_ip');

                $table->unique(['first_ip', 'last_ip']);
    
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

        Schema::drop('netblocks');
    }
}
