<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);

        $headers = ['Id', 'Contact', 'Name', 'Enabled'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $domain = Domain::all()->random();
        $contact = $domain->contact;

        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($contact->name, Artisan::output());
    }

    public function testFilter()
    {
        $domains = Domain::all()->pluck('name')->toArray();
        $filterDomain = array_pop($domains);
        $anotherDomain = array_pop($domains);

        $exitCode = Artisan::call(
            'domain:list',
            [
                '--filter' => $filterDomain,
            ]
        );
        $output = Artisan::output();
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($filterDomain, $output);
        $this->assertStringNotContainsString($anotherDomain, $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'domain:list',
            [
                '--filter' => 'domain_unknown.com',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No domain found for given filter.', Artisan::output());
    }
}
