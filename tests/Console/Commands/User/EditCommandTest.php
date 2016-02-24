<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

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

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments (missing: "user").
     */
    public function testWithoutUser()
    {
        Artisan::call('user:edit');
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
        $this->assertContains('Unable to find user with this criteria', Artisan::output());
    }

    public function testChangeAutoGenPassword()
    {
       $this->initDB();
       $exitCode = Artisan::call(
           'user:edit',
           [
               'user' => $this->dummy->id,
               'autogeneratepassword'
           ]
       );
       $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
        $this->assertContains('new password', $output);


    }

    public function testChangeFirstName()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'user:edit',
            [
                '--user' => $this->dummy->id,
                '--firstname' => "jip"
            ]
        );
        $this->assertEquals($exitCode, 0);

//dd(User::find($this->dummy->id)->toArray());
//        $this->assertEquals(
//            User::find($this->dummy->id)->first_name,
//            'jip'
//        );

        $output = Artisan::output();
        dd($output);
    }

    public function testChangeFirstNameWithPassword()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'user:edit',
            [
                '--user' => $this->dummy->id,
                '--firstname' => "jip",
                '--password' => "fbjldkjldj",
            ]
        );
        $this->assertEquals($exitCode, 0);


        $output = Artisan::output();
        dd($output);

    }

}
