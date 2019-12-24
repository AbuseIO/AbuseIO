<?php

namespace tests\Console\Commands\Brand;

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
            'brand:show',
            [
                'brand' => '1',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Name', 'Company name', 'Introduction text', 'Id'] as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call(
            'brand:show',
            [
                'brand' => 'AbuseIO',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('AbuseIO', Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'brand:show',
            [
                'brand' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching brand was found.', Artisan::output());
    }
}
