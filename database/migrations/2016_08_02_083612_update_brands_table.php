<?php

use AbuseIO\Models\Brand;
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
        // create the fields
        Schema::table('brands', function ($table) {
            $table->boolean('mail_custom_template')->default(false);
            $table->text('mail_template_plain')->default('');
            $table->text('mail_template_html')->default('');
            $table->boolean('ash_custom_template')->default(false);
            $table->text('ash_template')->default('');
        });

        // fill it with the default template
        $this->fillDefaultTemplates();
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

    /**
     * fill all brands with the default mail templates.
     *
     * @throws Exception
     */
    private function fillDefaultTemplates()
    {
        $templates = Brand::getDefaultMailTemplate();

        if (is_null($templates)) {
            throw new \Exception('Could not find the default mail templates, installation problem ?');
        }

        foreach (Brand::all() as $brand) {
            $brand->mail_template_plain = $templates['plain_mail'];
            $brand->mail_template_html = $templates['html_mail'];
            $brand->ash_template = Brand::getDefaultASHTemplate();
            $brand->save();
        }
    }
}
