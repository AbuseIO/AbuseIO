<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 *
 * @note This test assumes that there is a brand with ID 1 and name 'AbuseIO' in the database.
 */
class ShowCommandTest extends TestCase
{

    #[group('functional')]
    public function testBrandShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('brand:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "brand").', $output);
        $this->assertStringContainsString('Shows a brand based on the provided ID or name', $output);
    }

    #[group('functional')]
    public function testBrandShowCommandShouldPassWithValidIdFilter(): void
    {
        $headers = ['Id', 'Name', 'Company name', 'Introduction text'];

        $exitCode = Artisan::call(
            'brand:show',
            [
                'brand' => '1',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testBrandShowCommandWithValidNameFilter(): void
    {
        $exitCode = Artisan::call(
            'brand:show',
            [
                'brand' => 'AbuseIO',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('AbuseIO', Artisan::output());
    }

    #[group('functional')]
    public function testBrandShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'brand:show',
            [
                'brand' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching brand was found.', Artisan::output());
    }
}
