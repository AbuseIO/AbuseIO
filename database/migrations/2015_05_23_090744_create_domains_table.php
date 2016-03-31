<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
                // Columns
                $table->increments('id');
                $table->string('name')->unique();
                $table->integer('contact_id')->unsigned();
                $table->boolean('enabled')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                // Indexes
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
