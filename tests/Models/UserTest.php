<?php

namespace tests\Models;

use AbuseIO\Models\User;
use tests\TestCase;

class UserTest extends TestCase
{
    public function testUserModelFactory()
    {
        $user = factory(User::class)->make(['name' => 'testing name']);
        $this->assertEquals($user->name, 'testing name');
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
