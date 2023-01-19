<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    public function testWithoutId()
    {
        ob_start();
        $exitCode = Artisan::call('netblock:edit');
        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Edit a netblock', ob_get_clean());
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals(Command::INVALID, $exitCode);
        $this->assertStringContainsString('Unable to find netblock with this criteria', Artisan::output());
    }

    public function testWithInvalidContact()
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id'           => '1',
                '--contact_id' => '1000',
            ]
        );
        $this->assertEquals(Command::INVALID, $exitCode);
        $this->assertStringContainsString('Unable to find contact with this criteria', Artisan::output());
    }

    public function testEnabled()
    {
        $netblock = Netblock::find(1);
        if (!$netblock->enabled) {
            $this->enableNetblock($netblock);
        }
        $this->assertEquals(true, $netblock->enabled);
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id'        => '1',
                '--enabled' => 'false',
            ]
        );
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('The netblock has been updated', Artisan::output());

        $netblock = Netblock::find(1);
        $this->assertEquals(false, $netblock->enabled);
        $this->enableNetblock($netblock);
    }

    private function enableNetblock($netblock)
    {
        $netblock->update(['enabled' => true]);
    }
}
