<?php

namespace tests\Console\Commands\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use AbuseIO\Models\User;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testUserDeleteCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('user:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "user").', $output);
        $this->assertStringContainsString('Deletes a user from the system', $output);
    }

    #[group('functional')]
    public function testUserDeleteCommandShouldPassWithValidId(): void
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $exitCode = Artisan::call('user:delete', [
            'user' => $user->id
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString("The user has been deleted from the system", Artisan::output());
    }

    #[group('functional')]
    public function testUserDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'user:delete',
            [
                'user' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find user', Artisan::output());
    }
}
