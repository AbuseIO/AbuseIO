<?php

namespace tests\Models;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class EvidenceTest extends TestCase
{
    use DatabaseTransactions;

    private $eventId;

    private $evidenceId;

    private function initDB()
    {
        $event = factory(Event::class)->create();

        $this->eventId = $event->id;
        $this->evidenceId = $event->evidence_id;
    }

    /**
     * Testing the events() method on the model.
     */
    public function testHasManyEvents()
    {
        $this->initDB();

        $this->assertTrue(
            Evidence::find($this->evidenceId)->events->contains(
                Event::find($this->eventId)->id
            )
        );
    }
}
