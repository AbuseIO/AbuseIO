<?php

namespace tests\Console\Commands\Queue;

//use AbuseIO\Models\Job;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    //use DatabaseTransactions;

    /**
     * The list of testing fixtures to test against.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $list;

//    public function initDB()
//    {
//        $this->list = factory(Job::class, 10)->create();
//    }

//    public function testWithValidIdFilter()
//    {
//        //$this->initDB();
//
//        $exitCode = Artisan::call(
//            'queue:show',
//            [
//                'queue' => 'abuseio_collector'
//            ]
//        );
//        $this->assertEquals($exitCode, 0);
//        $output = Artisan::output();
//
//        foreach (['Id', 'Queue', 'Attempts',] as $el) {
//            $this->assertContains($el, $output);
//        }
//    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'queue:show',
            [
                'queue' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching queue was found.', Artisan::output());
    }
}
