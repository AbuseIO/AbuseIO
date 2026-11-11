<?php

namespace tests\Models;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    #[group("functional")]
    public function testModelFactory()
    {
        $account = Account::factory()->create();
        $accountFromDB = Account::where('name', $account->name)->first();
        $this->assertEquals($account->name, $accountFromDB->name);
    }

    #[group("functional")]
    public function testGetSystemAccount()
    {
        $this->assertTrue(
            Account::getSystemAccount()->is(Account::find(1))
        );
    }

    #[group("functional")]
    public function testSetSystemAccount()
    {
        $oldSysAdmin = Account::getSystemAccount();

        $account = Account::factory()->make();
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
