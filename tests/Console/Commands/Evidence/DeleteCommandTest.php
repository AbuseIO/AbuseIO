<?php

namespace tests\Console\Commands\Evidence;

use AbuseIO\Models\Evidence;
use AbuseIO\Models\Event;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $evidence;

//    private function initDB()
//    {
//        factory(Ticket::class)->create();
//
//        $this->evidence = factory(Evidence::class)->create();
//
//        factory(Event::class, ['evidence_id'=> $this->evidence->id])->create();
//    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'evidence:delete',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find evidence', Artisan::output());
    }

//    public function testValidIdButWithEvents()
//    {
//        $this->initDB();
//
//        $exitCode = Artisan::call(
//            'evidence:delete',
//            [
//                'id' => $this->evidence->id,
//            ]
//        );
//
//        $this->assertEquals($exitCode, 0);
//        $this->assertContains('Couldn\'t delete evidence because it is used in events' , Artisan::output());
//    }
}
