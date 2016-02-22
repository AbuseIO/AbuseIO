<?php

namespace tests\Console\Commands\Permission;

use AbuseIO\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $list;

    private function initDB()
    {
        $this->list = factory(Permission::class, 3)->create();
    }


    public function testHeaders()
    {
        $this->initDB();

        $exitCode = Artisan::call('permission:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Name', 'Description',];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();

        $exitCode = Artisan::call('permission:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains($this->list->get(0)->name, $output);
        $this->assertContains($this->list->get(1)->name, $output);
    }

    public function testFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'permission:list',
            [
                '--filter' => $this->list->get(0)->id,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();

        $this->assertContains((string)$this->list->get(0)->id, $output);
        $this->assertNotContains((string) $this->list->get(1)->id, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'permission:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No permission found for given filter.', Artisan::output());
    }
}
