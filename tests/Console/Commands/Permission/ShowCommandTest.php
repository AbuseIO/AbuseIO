<?php

namespace tests\Console\Commands\Permission;

use AbuseIO\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The list of testing fixtures to test against.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $list;

    public function initDB()
    {
        $this->list = factory(Permission::class, 10)->create();
    }

    public function testWithValidIdFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'permission:show',
            [
                'permission' => $this->list->get(0)->id,
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Id',  'Name', 'Description'] as $el) {
            $this->assertContains($el, $output);
        }
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'permission:show',
            [
                'permission' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching permission was found.', Artisan::output());
    }
}
