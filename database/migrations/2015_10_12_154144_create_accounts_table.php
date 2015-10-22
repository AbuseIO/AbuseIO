<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('description');
                $table->timestamps();
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
                'id'                        => 1,
                'name'                      => 'default',
                'description'               => 'The default account'
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
