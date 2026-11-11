<?php

namespace tests\Console\Commands\Collector;

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    #[group('functional')]
    public function testCollectorListCommandShouldPassShowingTableHeaders(): void
    {
        $headers = ['Name', 'Description'];

        $exitCode = Artisan::call('collector:list');
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testCollectorListCommandShouldPassShowingACollectorList(): void
    {
        $exitCode = Artisan::call('collector:list');
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Rbl', $output);
        $this->assertStringContainsString('Snds', $output);
    }

    #[group('functional')]
    public function testCollectorListCommandShouldPassWithValidFilter(): void
    {
        $exitCode = Artisan::call(
            'collector:list',
            [
                '--filter' => 'Rbl',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Rbl', $output);
        $this->assertStringNotContainsString('Snds', $output);
    }

    #[group('functional')]
    public function testCollectorListCommandShouldFailWithInvalidFilterValue(): void
    {
        $exitCode = Artisan::call(
            'collector:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No collectors found matching the filter.', Artisan::output());
    }
}
