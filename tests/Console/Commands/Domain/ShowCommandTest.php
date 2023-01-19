<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidNameFilter()
    {
        $domains = Domain::all()->pluck('name')->toArray();
        $domain = array_pop($domains);
        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => $domain,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($domain, Artisan::output());
    }

    public function testWithValidIdFilter()
    {
        $domain = Domain::find(1)->name;

        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => '1',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($domain, Artisan::output());
    }

    public function testWithInvalidIdFilter()
    {
        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => '1000',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No matching domain was found.', Artisan::output());
    }
}
