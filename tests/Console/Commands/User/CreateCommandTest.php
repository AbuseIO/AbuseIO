<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function testWithoutArguments()
    {
        //        Artisan::call('user:create');
//        $output = Artisan::output();
//
//        $this->assertContains('The first name field is required.', $output);
//        $this->assertContains('Failed to create the user due to validation warnings', $output);
    }

    public function testCreateValid()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'password'   => 'jiperish',
            'account'    => $user->account_id,
            'language'   => $user->locale,
            'disabled'   => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertContains('The user has been created', $output);
    }

    public function testNoValidAccountCreateValid()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'password'   => 'jiperish',
            'account'    => 'not_a_valid_account_name',
            'language'   => $user->locale,
            'disabled'   => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertContains('No account was found for given account name ', $output);
        $this->assertContains('The user has been created', $output);
    }

    public function testWithoutDisabledArgument()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'password'   => 'jiberish',
            'account'    => 'Default',
            'language'   => $user->locale,
        ]);
        $output = Artisan::output();

        $this->assertFalse(
            (bool) User::find(
                $this->returnIdFromSuccessOutput($output)
            )->disabled
        );

        $this->assertContains('The user has been created', $output);
    }

    public function testWithDisabledArgumentTrue()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'password'   => 'jiperish',
            'account'    => 'Default',
            'language'   => $user->locale,
            'disabled'   => 'true',
        ]);
        $output = Artisan::output();

        $this->assertTrue(
            (bool) User::find(
                $this->returnIdFromSuccessOutput($output)
            )->disabled
        );

        $this->assertContains('The user has been created', $output);
    }

    public function testIfNoPasswordIsSuppliedPasswordIsGenerated()
    {
        $user = factory(User::class)->make();

        Artisan::call('user:create', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,

            'account'  => 'Default',
            'language' => $user->locale,
            'disabled' => $user->disabled,
        ]);
        $output = Artisan::output();

        $this->assertContains('Using auto generated password: ', $output);
        $this->assertContains('The user has been created', $output);
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
}
