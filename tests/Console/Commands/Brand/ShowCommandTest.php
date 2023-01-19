<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

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
        $this->assertEquals(Command::SUCCESS, $exitCode);
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
        $this->assertEquals(Command::SUCCESS, $exitCode);
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

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No matching brand was found.', Artisan::output());
    }
}
