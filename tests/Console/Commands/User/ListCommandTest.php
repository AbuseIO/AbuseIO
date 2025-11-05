<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testUserListCommand()
    {
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();

        $exitCode = Artisan::call(
            'user:list',
            [
                //
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($user->email, Artisan::output());
    }

    public function testUserListCommandWithValidFilter()
    {
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();
        $other_user = User::where('id', '!=', $user->id)->inRandomOrder()->first() ?? User::factory()->create();

        $exitCode = Artisan::call(
            'user:list',
            [
                '--filter' => $user->email,
            ]
        );

        $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
        $this->assertStringContainsString($user->email, $output);
        $this->assertStringNotContainsString($other_user->email, $output);
    }
}
