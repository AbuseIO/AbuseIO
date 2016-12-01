<?php

namespace tests\Models;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class AccountTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $account = factory(Account::class)->create();
        $accountFromDB = Account::where('name', $account->name)->first();
        $this->assertEquals($account->name, $accountFromDB->name);
    }

    public function testGetSystemAccount()
    {
        $this->assertTrue(
            Account::getSystemAccount()->is(Account::find(1))
        );
    }

    public function testSetSystemAccount()
    {
        $oldSysAdmin = Account::getSystemAccount();

        $account = factory(Account::class)->create();
        $account->systemaccount = true;
        $account->save();

        $newSysAdmin = Account::getSystemAccount();

        $this->assertTrue(
            $newSysAdmin->is($account)
        );

        $this->assertFalse(
            $newSysAdmin->is($oldSysAdmin)
        );
    }
}
