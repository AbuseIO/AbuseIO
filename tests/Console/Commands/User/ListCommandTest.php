<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testUserListCommand()
    {
        $user = User::all()->random();

        $exitCode = Artisan::call(
            'user:list',
            [
                //
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($user->email, Artisan::output());
    }

    public function testUserListCommandWithValidFilter()
    {
        $user = User::all()->random();
        $other_user = User::where('id', '!=', $user->id)->get()->random();

        $exitCode = Artisan::call(
            'user:list',
            [
                '--filter' => $user->email,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString($user->email, $output);
        $this->assertStringNotContainsString($other_user->email, $output);
    }
}
