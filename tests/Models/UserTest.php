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
}
