<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testBrandCreateCommandShouldFailWithoutArguments(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "name, company_name").');
        Artisan::call('brand:create');
    }

    #[group('functional')]
    public function testBrandCreateCommandShouldPassWithValidValues(): void
    {
        $exitCode = Artisan::call('brand:create', [
            'name' => 'Test name',
            'company_name' => 'Test company name',
            'introduction_text' => 'Just do it',
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The brand has been created', Artisan::output());
    }
}
