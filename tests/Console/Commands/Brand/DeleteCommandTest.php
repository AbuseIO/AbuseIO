<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testBrandDeleteCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('brand:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Deletes a brand from the system', $output);
    }

    #[group('functional')]
    public function testBrandDeleteCommandShouldPassWithValidId(): void
    {
        $brand = Brand::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $exitCode = Artisan::call('brand:delete', [
            'id' => $brand->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString("The brand has been deleted from the system", Artisan::output());
    }

    #[group('functional')]
    public function testBrandDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'brand:delete',
            [
                'id' => 1000,
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find brand', Artisan::output());
    }
}
