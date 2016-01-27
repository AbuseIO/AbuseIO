<?php

namespace tests\Models;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountTest extends \TestCase
{
    use DatabaseTransactions;

    function testModelFactory()
    {
        $account = factory(Account::class)->create();
        $accountFromDB = Account::where("name", $account->name)->first();
        $this->assertEquals($account->name, $accountFromDB->name);
    }
}

