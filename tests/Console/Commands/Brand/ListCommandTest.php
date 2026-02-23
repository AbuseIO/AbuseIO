<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testBrandListCommandHeadersShouldPass(): void
    {
        $headers = ['Id', 'Name', 'Company name'];

        $exitCode = Artisan::call('brand:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testBrandListCommandShouldPassWhenBrandIsListed(): void
    {
        $brand = Brand::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $exitCode = Artisan::call('brand:list');

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($brand->name, Artisan::output());
    }

    #[group('functional')]
    public function testBrandListCommandShouldPassWhenOnlyFilteredValueIsGiven(): void
    {
        $brandOne = Brand::create([
            'name' => 'Functional Test Brand no. 1',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);
        $brandTwo = Brand::create([
            'name' => 'Functional Test Brand no. 2',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $exitCode = Artisan::call(
            'brand:list',
            [
                '--filter' => $brandOne->name,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($brandOne->name, $output);
        $this->assertStringNotContainsString($brandTwo->name, $output);
    }

    #[group('functional')]
    public function testBrandListCommandShouldFailWithInvalidFilterValue(): void
    {
        $exitCode = Artisan::call(
            'brand:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No brands found.', Artisan::output());
    }
}
