<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use DatabaseTransactions;

//    public function testWithoutArguments()
//    {
//        //        Artisan::call('user:create');
    ////        $output = Artisan::output();
    ////
    ////        $this->assertStringContainsString('The first name field is required.', $output);
    ////        $this->assertStringContainsString('Failed to create the user due to validation warnings', $output);
//    }

    public function testCreateValid()
    {
        $user = factory(User::class)->make();

        $password = 'jiperish';

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name'  => $user->last_name,
            'email'        => $user->email,
            '--password'   => $password,
            'account'      => $user->account_id,
            '--language'   => $user->locale,
            '--disabled'   => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertUsers($user, $this->findUserWithOutput($output), $password);

        $this->assertStringContainsString('The user has been created', $output);
    }

    public function testNoValidAccountCreateValid()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name'  => $user->last_name,
            'email'        => $user->email,
            '--password'   => 'jiperish',
            'account'      => 'not_a_valid_account_name',
            '--language'   => $user->locale,
            '--disabled'   => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertStringContainsString('No account was found for given account name ', $output);
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

    public function testWithoutDisabledArgument()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name'  => $user->last_name,
            'email'        => $user->email,
            '--password'   => 'jiberish',
            'account'      => 'Default',
            '--language'   => $user->locale,
        ]);
        $output = Artisan::output();

        $this->assertFalse(
            (bool) $this->findUserWithOutput($output)->disabled
        );

        $this->assertStringContainsString('The user has been created', $output);
    }

    public function testWithDisabledArgumentTrue()
    {
        $user = factory(User::class)->make();

        $password = 'jiperish';

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name'  => $user->last_name,
            'email'        => $user->email,
            '--password'   => $password,
            'account'      => 'Default',
            '--language'   => $user->locale,
            '--disabled'   => 'true',
        ]);
        $output = Artisan::output();

        $this->assertTrue(
            (bool) $this->findUserWithOutput($output)->disabled
        );

        $this->assertUsers(
            $user,
            $this->findUserWithOutput($output),
            $password
        );

        $this->assertStringContainsString('The user has been created', $output);
    }

    public function testIfNoPasswordIsSuppliedPasswordIsGenerated()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            '--first_name' => $user->first_name,
            '--last_name'  => $user->last_name,
            'email'        => $user->email,

            'account'    => 'Default',
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
