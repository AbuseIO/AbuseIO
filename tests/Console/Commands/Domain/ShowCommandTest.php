<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidNameFilter()
    {
        $domain = Domain::factory()->create();
        $exitCode = Artisan::call('domain:show', ['domain' => $domain->name]);

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($domain->name, Artisan::output());
    }

    public function testWithValidIdFilter()
    {
        $domain = Domain::factory()->create();
        $exitCode = Artisan::call('domain:show', ['domain' => (string) $domain->id]);

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($domain->name, Artisan::output());
    }

    public function testWithInvalidIdFilter()
    {
        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => '1000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching domain was found.', Artisan::output());
    }
}
