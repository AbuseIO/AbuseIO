<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'accounts',
            function (Blueprint $table) {
                // Columns
                $table->increments('id');
                $table->string('name', 80)->unique();
                $table->string('description');
                $table->boolean('disabled')->default(false);
                $table->integer('brand_id')->unsigned();
                $table->boolean('systemaccount')->default(false);
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index('brand_id');
            }
        );

        $this->addDefaultAccount();
    }

    public function addDefaultAccount()
    {
        // Always recreate the default account for the system
        DB::table('accounts')->where('id', '=', '1')->delete();

        $accounts = [
            [
                'id'            => 1,
                'name'          => 'Default',
                'description'   => 'Default system account',
                'disabled'      => false,
                'brand_id'      => 1,
                'systemaccount' => true,
                'created_at'    => new DateTime(),
                'updated_at'    => new DateTime(),
            ],
        ];

        DB::table('accounts')->insert($accounts);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');
    }
}
