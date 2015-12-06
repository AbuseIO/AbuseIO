<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'domains',
            function (Blueprint $table) {

                $table->increments('id');
                $table->string('name')->unique();
                $table->integer('contact_id')->unsigned();
                $table->boolean('enabled')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                $table->index('name');
                $table->index('contact_id');
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
        Schema::drop('domains');
    }
}
