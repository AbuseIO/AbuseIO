<?php

namespace tests\Console\Commands\User;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $dummy;

    private function initDB()
    {
        $this->dummy = factory(User::class)->create();
    }

    public function testWithoutUser()
    {
        ob_start();
        Artisan::call('user:edit');
        $this->assertStringContainsString('Edit a user', ob_get_clean());
    }

    public function testWithInvalidUser()
    {
        $exitCode = Artisan::call(
            'user:edit',
            [
                'user' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find user with this criteria', Artisan::output());
    }

    public function testChangeFirstName()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'user:edit',
            [
                'user'         => $this->dummy->id,
                '--first_name' => 'jip',
            ]
        );
        $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
        $this->assertStringContainsString(
            'The user has been updated',
            $output
        );
    }

    public function testChangeFirstNameWithPassword()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'user:edit',
            [
                'user'         => $this->dummy->id,
                '--first_name' => 'jip',
                '--password'   => 'fbjldkjldj',
            ]
        );
        $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
        $this->assertStringContainsString(
            'The user has been updated',
            $output
        );
    }

    public function testChangeWithAutoPassword()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'user:edit',
            [
                'user'           => $this->dummy->id,
                '--autopassword' => 'some dummy value', // I don't know how to test a InputOption::VALUE_NONE but this works
            ]
        );
        $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
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
