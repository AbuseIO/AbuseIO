<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $name1;

    private $name2;

    private $brands;

    /**
     * this should not be part of the setUp method because the database connection
     * has NOT been setUp properly at that moment.
     */
    private function initDB()
    {
        Brand::where('id', '!=', 1)->delete();

        $this->brands = factory(Brand::class, 10)->create();

        $this->name1 = $this->brands->first()->name;
        $this->name2 = $this->brands->get(1)->name;
    }

    public function testHeaders()
    {
        $this->initDB();
        $exitCode = Artisan::call('brand:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Name', 'Company name'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();

        $exitCode = Artisan::call('brand:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->name1, $output);
    }

    public function testFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'brand:list',
            [
                '--filter' => $this->name2,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->name2, $output);
        $this->assertStringNotContainsString($this->name1, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'brand:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No brand found for given filter.', Artisan::output());
    }
}
