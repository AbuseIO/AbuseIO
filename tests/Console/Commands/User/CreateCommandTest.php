<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testCreateUserCommandShouldFailWithoutArguments(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "email")');
        Artisan::call('user:create');
    }

    #[group('functional')]
    public function testUserCreateCommandShouldFailWithValidAttributes(): void
    {
        $password = 'testpassword';
        $user = User::make([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        Artisan::call('user:create', [
            'email' => $user->email,
            'account' => $user->account_id,
            '--password' => $password,
            '--first_name' => $user->first_name,
            '--last_name' => $user->last_name,
            '--language' => $user->locale,
            '--disabled' => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertUsers($user, $this->findUserWithOutput($output), $password);
        $this->assertStringContainsString('The user has been created', $output);
    }

    #[group('functional')]
    public function testUserCreateCommandShouldFailWithNoValidAccount(): void
    {
        $user = new User([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name' => $user->last_name,
            'email' => $user->email,
            '--password' => 'jiperish',
            'account' => 'not_a_valid_account_name',
            '--language' => $user->locale,
            '--disabled' => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertStringContainsString('No account was found for given account name ', $output);
        $this->assertStringContainsString('The user has been created', $output);
    }

    #[group('functional')]
    public function testUserCreateCommandShouldPassWithoutDisabledArgument(): void
    {
        $user = new User([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $exitCode = Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name' => $user->last_name,
            'email' => $user->email,
            '--password' => 'jiberish',
            'account' => 'Default',
            '--language' => $user->locale,
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertFalse(
            (bool)$this->findUserWithOutput($output)->disabled
        );
        $this->assertStringContainsString('The user has been created', $output);
    }

    #[group('functional')]
    public function testUserCreateCommandShouldPassWithDisabledArgumentTrue(): void
    {
        $user = new User([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $password = 'jiperish';

        $exitCode = Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name' => $user->last_name,
            'email' => $user->email,
            '--password' => $password,
            'account' => 'Default',
            '--language' => $user->locale,
            '--disabled' => 'true',
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertTrue(
            (bool)$this->findUserWithOutput($output)->disabled
        );
        $this->assertUsers(
            $user,
            $this->findUserWithOutput($output),
            $password
        );
        $this->assertStringContainsString('The user has been created', $output);
    }

    #[group('functional')]
    public function testUserCreateCommandShouldPassIfNoPasswordIsSuppliedPasswordShouldBeGenerated(): void
    {
        $user = User::factory()->make();

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name' => $user->last_name,
            'email' => $user->email,

            'account' => 'Default',
            '--language' => $user->locale,
            '--disabled' => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertUsers(
            $user,
            $this->findUserWithOutput($output),
            $this->returnGeneratedPasswordWithOutput($output)
        );

        $this->assertStringContainsString('Using auto generated password: ', $output);
        $this->assertStringContainsString('The user has been created', $output);
    }

    private function assertUsers($user1, $user2, $password)
    {
        $this->assertEquals(
            $user1->first_name,
            $user2->first_name
        );

        $this->assertEquals(
            $user1->last_name,
            $user2->last_name
        );

        $this->assertTrue(
            Hash::check($password, $user2->password),
            'The password is not correct'
        );
    }

    /**
     * @param $output
     *
     * @return $id
     */
    protected function returnIdFromSuccessOutput($output)
    {
        $startPos = strpos($output, 'id: ') + 4;
        $endPos = strpos($output, ')');
        $length = $endPos - $startPos;

        return substr($output, $startPos, $length);
    }

    protected function findUserWithOutput($output)
    {
        return User::find(
            $this->returnIdFromSuccessOutput($output)
        );
    }

    private function returnGeneratedPasswordWithOutput($output)
    {
        return sscanf($output, 'Using auto generated password: %s\nThe user has been created (id: %d)\n')[0];
    }
}
