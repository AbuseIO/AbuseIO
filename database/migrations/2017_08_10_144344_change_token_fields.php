<?php

use AbuseIO\Models\Account;
use Illuminate\Database\Migrations\Migration;

class ChangeTokenFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // the tickets where designed for md5 hashes and not uuid4 tokens
        // correct the size of the columns

        DB::statement('ALTER TABLE accounts MODIFY COLUMN token varchar(36)');
        DB::statement('ALTER TABLE tickets MODIFY COLUMN remote_api_token varchar(36)');
        DB::statement('ALTER TABLE tickets MODIFY COLUMN api_token varchar(36)');
        DB::statement('ALTER TABLE contacts MODIFY COLUMN token varchar(36)');

        // set all the accounts api tokens to uuids
        Account::all()->each(function ($account, $key) {
            $account->token = generateApiToken();
            $account->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // nothing to do
    }
}
