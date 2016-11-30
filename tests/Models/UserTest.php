<?php

namespace tests\Models;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserModelFactory()
    {
        $user = factory(User::class)->make(['first_name' => 'testing name']);
        $this->assertEquals($user->first_name, 'testing name');
    }

    public function testInverseValueSystemAccount()
    {
        $user = factory(User::class)->create();
        $oldState = $user->account->systemaccount;

        if ($user->account->isSystemAccount() === '1') {
            $user->account->systemaccount = '0';
        } else {
            $user->account->systemaccount = '1';
        }

        $this->assertNotEquals($user->account->systemaccount, $oldState);
    }

    public function testMayLoginSystemAccount()
    {
        $user = factory(User::class)->make();
        $user->account->systemaccount = true;

        $messages = [];

        $this->assertTrue($user->mayLogin($messages));
        $this->assertTrue(empty($messages));
    }

    public function testMayLoginWithDisabledAccount()
    {
        $user = factory(User::class)->make();
        $user->account->systemaccount = false;
        $user->account->disabled = true;
        $user->disabled = false;

        $messages = [];

        $this->assertFalse($user->mayLogin($messages));
        $this->assertContains('The account Default for this login is disabled.', $messages);
    }

    public function testMayLoginWithDisabledUser()
    {
        $user = factory(User::class)->make();
        $user->account->systemaccount = false;
        $user->account->disabled = false;

        $user->disabled = true;

        $messages = [];

        $this->assertFalse($user->mayLogin($messages));
        $this->assertContains('This login is disabled.', $messages);
    }
}
