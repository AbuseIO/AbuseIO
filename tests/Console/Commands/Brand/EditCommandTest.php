<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class EditCommandTest.
 *
 * @note This test has one brand already seeded in the database from the migrations.
 */
class EditCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testBrandEditCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('brand:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id").', $output);
        $this->assertStringContainsString('Edits an existing brand', $output);
    }

    #[group('functional')]
    public function testBrandEditCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'brand:edit',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find brand with this criteria', Artisan::output());
    }

    #[group('integration')]
    public function testBrandEditCommandShouldPassWithChangedNames(): void
    {
        $exitCode = Artisan::call(
            'brand:edit',
            [
                'id' => '1',
                '--name' => 'New name',
            ]
        );

        $brand = Brand::where('id', 1)->first();

        $this->assertEquals(0, $exitCode);
        $this->assertEquals('New name', $brand->name);
    }

    #[group('integration')]
    public function testBrandEditCommandShouldPassWithChangedCompanyName(): void
    {
        $exitCode = Artisan::call(
            'brand:edit',
            [
                'id' => '1',
                '--company_name' => 'New company name',
            ]
        );

        $brand = Brand::find(1);

        $this->assertEquals(0, $exitCode);
        $this->assertEquals('New company name', $brand->company_name);
    }
}
