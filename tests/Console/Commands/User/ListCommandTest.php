<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testUserListCommandShouldPassToShowUsers(): void
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
            'user:list',
            [
                //
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($user->email, Artisan::output());
    }

    #[group('functional')]
    public function testUserListCommandShouldPassWithValidFilter(): void
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);
        $other_user = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'janedoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $exitCode = Artisan::call(
            'user:list',
            [
                '--filter' => $user->email,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($user->email, $output);
        $this->assertStringNotContainsString($other_user->email, $output);
    }

    #[group('functional')]
    public function testUserListCommandShouldFailWithInvalidFilter(): void
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
            'user:list',
            [
                '--filter' => 'filterwithnoresult',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringNotContainsString($user->email, $output);
    }

    #[group('functional')]
    public function testUserListCommandShouldShowCorrectRoleDescriptions(): void
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);
        $roleOne = $user->roles()->create([
            'name' => 'admin',
            'description' => 'Administrator',
        ]);
        $roleTwo = $user->roles()->create([
            'name' => 'Test Admin Role',
            'description' => 'Test Description',
        ]);

        $exitCode = Artisan::call(
            'user:list',
            [
                '--filter' => $user->email,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($roleOne->description, $output);
        $this->assertStringContainsString($roleTwo->description, $output);
    }
}
