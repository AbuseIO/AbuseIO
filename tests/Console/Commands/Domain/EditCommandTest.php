<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
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
        Artisan::call('domain:edit');
        $output = ob_get_clean();
        $this->assertStringContainsString('Edit a domain', $output);
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals(Command::INVALID, $exitCode);
        $this->assertStringContainsString('Unable to find domain with this criteria', Artisan::output());
    }

    public function testWithInvalidContact()
    {
        $exitCode = Artisan::call(
            'domain:edit',
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
        $domain = Domain::find(1);
        if (!$domain->enabled) {
            $this->enableDomain($domain);
        }
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id'        => '1',
                '--enabled' => 'false',
            ]
        );
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('The domain has been updated', Artisan::output());
        $domain = Domain::find(1);
        $this->assertEquals(false, $domain->enabled);
        $this->enableDomain($domain);
    }

    private function enableDomain($domain)
    {
        $domain->update(['enabled' => true]);
    }
}
