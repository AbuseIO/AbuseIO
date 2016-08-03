<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create the
        Schema::table('brands', function ($table) {
            $table->boolean('mail_custom_template')->default(false);
            $table->text('mail_template_plain')->default('');
            $table->text('mail_template_html')->default('');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // the create migration drops the table
        // nothing to do
    }
}
