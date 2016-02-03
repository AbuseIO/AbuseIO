<?php

namespace tests\Models;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends \TestCase
{
    use DatabaseTransactions;

    /** @var  int  $eventId*/
    private $eventId;

    /** @var int $evidenceId */
    private $evidenceId;

    private function initDB()
    {
        $event = factory(Event::class)->create();

        $this->eventId = $event->id;
        $this->evidenceId = $event->evidence_id;
    }

    function testBelongsToRelation()
    {
        $this->initDB();

        $this->assertEquals(
            Evidence::find($this->evidenceId),
            Event::find($this->eventId)->evidence
        );
    }

    public function testModelFactory()
    {
        $event = factory(Event::class)->create();
        $eventFromDB = Event::where("source", $event->source)->first();
        $this->assertEquals($event->source, $eventFromDB->source);
    }
}

