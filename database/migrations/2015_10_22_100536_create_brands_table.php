<?php

use AbuseIO\Models\Brand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'brands',
            function (Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->string('name', 80)->unique();
                $table->string('company_name', 80);
                $table->string('introduction_text');
                $table->integer('creator_id')->unsigned();
                $table->binary('logo');
                $table->boolean('systembrand')->default(false);
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('creator_id');
            }
        );

        $this->addDefaultBrand();
    }

    /**
     * Add the default branding.
     */
    public function addDefaultBrand()
    {
        // Always recreate the default branding for the system
        DB::table('brands')->where('id', '=', '1')->delete();

        $brands = [
            [
                'id'                => 1,
                'name'              => 'AbuseIO',
                'company_name'      => 'AbuseIO',
                'introduction_text' => 'Open Source abusemanagement',
                'creator_id'        => 1,
                'logo'              => file_get_contents(Brand::getDefaultLogo()->getPathname()),
                'systembrand'       => true,
                'created_at'        => new DateTime(),
                'updated_at'        => new DateTime(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brands');
    }
}
