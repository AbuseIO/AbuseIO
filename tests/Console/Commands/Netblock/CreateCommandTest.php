<?php
namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

/**
 * Class CreateCommandTest
 * @package tests\Console\Commands\Netblock
 */
class CreateCommandTest extends TestCase
{

    public function testCreate()
    {
        $exitCode = Artisan::call(
            'netblock:create',
            [
                '--contact'     => '1',
                '--first_ip'    => '192.168.0.0',
                '--last_ip'     => '192.168.255.255',
                '--description' => '16-bit block',
                '--enabled'     => 'true'
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertContains("created", Artisan::output());

        $this->seed("NetblocksTableSeeder");
    }

    public function testCreateWithoutParams()
    {
        $exitCode = Artisan::call("netblock:create");
        $this->assertEquals(0, $exitCode);
        $this->assertContains("Failed to find contact, could\'nt save netblock", Artisan::output());
    }

    public function testCreateWithoutParamsButValidUser()
    {
        $exitCode = Artisan::call(
            'netblock:create',
            [
                '--contact' => '1'
            ]
        );
        $this->assertEquals(0, $exitCode);
        $this->assertContains(
            "The first ip must be a valid IP address.\nThe last ip must be a valid IP address.\n" .
            "Failed to create the netblock due to validation warnings\n",
            Artisan::output()
        );
    }
}
