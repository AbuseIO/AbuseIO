<?php

namespace tests\Console\Commands\Domain;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => 'john-doe.tld',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('john-doe.tld', Artisan::output());
    }

    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => '1',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('john-doe.tld', Artisan::output());
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
