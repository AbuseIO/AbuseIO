<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testUserEditCommandShouldFailWithoutUser(): void
    {
        $exitCode = Artisan::call('user:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "user")', $output);
        $this->assertStringContainsString('Edits an existing user in the system.', $output);
    }

    #[group('functional')]
    public function testUserEditCommandShouldFailWithInvalidUser(): void
    {
        $exitCode = Artisan::call(
            'user:edit',
            [
                'user' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find user with this criteria', Artisan::output());
    }

    #[group('functional')]
    public function testUserEditCommandShouldPassChangeFirstName(): void
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
            'user:edit',
            [
                'user'         => $user->id,
                '--first_name' => 'Johnny',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The user has been updated', $output);
    }

    #[group('functional')]
    public function testUserEditCommandShouldPassChangingFirstNameWithPassword(): void
    {
        $password = 'JohnOldDog';
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt($password),
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $exitCode = Artisan::call(
            'user:edit',
            [
                'user'         => $user->id,
                '--first_name' => 'Johnny',
                '--password'   => 'JohnNewDog',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString(
            'The user has been updated',
            Artisan::output()
        );
    }

    #[group('functional')]
    public function testUserEditCommandShouldChangeWithAutoPassword(): void
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
            'user:edit',
            [
                'user'           => $user->id,
                '--autopassword' => 'some dummy value', // I don't know how to test a InputOption::VALUE_NONE but this works
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString(
            'The user has been updated',
            $output
        );
        $this->assertStringContainsString(
            'Using auto generated password',
            $output
        );
    }
}
