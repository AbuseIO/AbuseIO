<?php

namespace tests\Models;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;

class EventTest extends \TestCase
{

    function testBelongsToRelation()
    {
        $this->assertEquals(
            Evidence::find(1),
            Event::find(1)->evidence
        );
    }

    public function testModelFactory()
    {
        $event = factory(Event::class)->create();
        $eventFromDB = Event::where("source", $event->source)->first();
        $this->assertEquals($event->source, $eventFromDB->source);
    }
}

