<?php

namespace tests\Models;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[group('unit')]
    public function testUserModelFactory()
    {
        $user = User::factory()->make(['first_name' => 'testing name']);
        $this->assertEquals($user->first_name, 'testing name');
    }

    #[group('unit')]
    public function testInverseValueSystemAccount()
    {
        $user = User::factory()->make();
        $oldState = $user->account->isSystemAccount();

        if ($user->account->isSystemAccount()) {
            $user->account->systemaccount = false;
        } else {
            $user->account->systemaccount = true;
        }

        $this->assertNotEquals($user->account->isSystemAccount(), $oldState);
    }

    #[group('unit')]
    public function testMayLoginSystemAccount()
    {
        $user = User::factory()->make();
        $user->account->systemaccount = true;

        $messages = [];

        $this->assertTrue($user->mayLogin($messages));
        $this->assertTrue(empty($messages));
    }

    #[group('unit')]
    public function testMayLoginWithDisabledAccount()
    {
        $user = User::factory()->make();
        $user->account->systemaccount = false;
        $user->account->disabled = true;
        $user->disabled = false;

        $messages = [];

        $this->assertFalse($user->mayLogin($messages));
        $this->assertContains('The account Default for this login is disabled.', $messages);
    }

    #[group('unit')]
    public function testMayLoginWithDisabledUser()
    {
        $user = User::factory()->make();
        $user->account->systemaccount = false;
        $user->account->disabled = false;

        $user->disabled = true;

        $messages = [];

        $this->assertFalse($user->mayLogin($messages));
        $this->assertContains('This login is disabled.', $messages);
    }
}
