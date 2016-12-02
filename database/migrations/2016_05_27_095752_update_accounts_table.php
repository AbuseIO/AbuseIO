<?php

use AbuseIO\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add a token column to the table
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('token');
        });

        // fill the token in the systemaccount
        $this->updateSystemAccount();
    }

    public function updateSystemAccount()
    {
        $system_account = Account::find(1);
        $system_account->token = md5('replace me');
        $system_account->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // put this code here because in 5.3 users can rollback by steps.
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
}
