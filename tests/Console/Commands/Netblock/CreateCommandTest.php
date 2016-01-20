<?php

namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testCreate()
    {
        $exitCode = Artisan::call(
            'netblock:create',
            [
                'contact' => '1',
                'first_ip' => '192.168.0.0',
                'last_ip' => '192.168.255.255',
                'description' => '16-bit block',
                'enabled' => 'true',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertContains('created', Artisan::output());

        $this->seed('NetblocksTableSeeder');
    }

    public function testCreateWithoutParams()
    {
        $exitCode = Artisan::call('netblock:create');
        $this->assertEquals(0, $exitCode);
        $this->assertContains('The description field is required.', Artisan::output());
    }

    public function testCreateWithoutParamsButValidUser()
    {
        $exitCode = Artisan::call(
            'netblock:create',
            [
                'contact' => '1',
            ]
        );
        $this->assertEquals(0, $exitCode);
        $this->assertNotContains(
            'The contact id field is required',
            Artisan::output()
        );
    }
}
