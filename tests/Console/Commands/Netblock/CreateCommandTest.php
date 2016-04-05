<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
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

        $this->assertEquals(0, $exitCode);
        $this->assertContains('created', Artisan::output());

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
        //        $exitCode = Artisan::call('netblock:create');
//        $this->assertEquals(0, $exitCode);
//        $this->assertContains('The description field is required.', Artisan::output());
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
