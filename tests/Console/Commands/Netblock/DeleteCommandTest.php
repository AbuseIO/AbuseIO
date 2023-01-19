<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    public function testValid()
    {
        $exitCode = Artisan::call(
            'netblock:delete',
            [
                'id' => '1',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('netblock has been deleted', Artisan::output());

        Netblock::withTrashed()->find(1)->restore();
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'netblock:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(Command::INVALID, $exitCode);
        $this->assertStringContainsString('Unable to find netblock', Artisan::output());
    }
}
