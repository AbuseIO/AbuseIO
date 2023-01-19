<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testCreate()
    {
        /** @var Netblock $dummyBlock */
        $dummyBlock = factory(Netblock::class)->make();

        $exitCode = Artisan::call(
            'netblock:create',
            [
                'contact'     => $dummyBlock->contact_id,
                'first_ip'    => $dummyBlock->first_ip,
                'last_ip'     => $dummyBlock->last_ip,
                'description' => $dummyBlock->description,
                'enabled'     => $dummyBlock->enabled,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('created', Artisan::output());

        Netblock::where([
            'contact_id'  => $dummyBlock->contact_id,
            'first_ip'    => $dummyBlock->first_ip,
            'last_ip'     => $dummyBlock->last_ip,
            'description' => $dummyBlock->description,
            'enabled'     => $dummyBlock->enabled,
        ])->forceDelete();

        //$this->seed('NetblocksTableSeeder');
    }

    public function testWithoutArguments()
    {
        ob_start();
        $exitCode = Artisan::call('netblock:create');
        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Creates a new netblock', ob_get_clean());
    }

    public function testCreateWithoutParamsButValidUser()
    {
        ob_start();
        $exitCode = Artisan::call(
            'netblock:create',
            [
                'contact' => '1',
            ]
        );
        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Creates a new netblock', ob_get_clean());
    }
}
