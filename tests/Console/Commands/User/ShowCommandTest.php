<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testUserShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('user:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "user").', $output);
        $this->assertStringContainsString('Shows a user', $output);
    }

    #[group('functional')]
    public function testUserShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'user:show',
            [
                'user' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching user was found.', Artisan::output());
    }

    #[group('functional')]
    public function testUserShowCommandWithValidFilter(): void
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $exitCode = Artisan::call(
            'user:show',
            [
                'user' => $user->id,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($user->first_name, Artisan::output());
    }
}
