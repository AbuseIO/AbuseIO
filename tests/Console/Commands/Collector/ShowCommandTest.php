<?php

namespace tests\Console\Commands\Collector;

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{

    #[group('functional')]
    public function testCollectorShowCommandShouldFailWithoutFilter(): void
    {
        $exitCode = Artisan::call('collector:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "collector")', $output);
        $this->assertStringContainsString('Shows a collector', $output);
    }

    #[group('functional')]
    public function testCollectorShowCommandShouldPassWithValidIdFilter(): void
    {
        $headers = ['Name', 'Description', 'Enabled', 'Location', 'Key'];

        $exitCode = Artisan::call(
            'collector:show',
            [
                'collector' => 'Snds',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testCollectorShowCommandShouldPassWithValidNameFilter(): void
    {
        $exitCode = Artisan::call(
            'collector:show',
            [
                'collector' => 'Snds',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Collects data from Microsoft SNDS to generate events', Artisan::output());
    }

    #[group('functional')]
    public function testCollectorShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'collector:show',
            [
                'collector' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching collector was found.', Artisan::output());
    }
}
