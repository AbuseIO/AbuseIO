<?php

namespace tests\Console\Commands\Collector;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call(
            'collector:show',
            [
                'collector' => 'Snds',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Name', 'Description', 'Enabled', 'Location', 'Key'] as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call(
            'collector:show',
            [
                'collector' => 'Snds',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Collects data from Microsoft SNDS to generate events', Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'collector:show',
            [
                'collector' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching collector was found.', Artisan::output());
    }
}
