<?php

use AbuseIO\Models\Account;
use Illuminate\Database\Migrations\Migration;

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
        Schema::table('accounts', function ($table) {
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
        // nothing to do, table is dropped in the create migration
    }
}
