<?php

namespace tests\Models;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    #[group("unit")]
    public function testIfEventsPutInEvidenceIsVisibleInEvidence() {
        $evidence = Evidence::make([
            'filename' => 'example.txt',
            'sender' => 'John',
            'subject' => 'Test Evidence',
        ]);

        $event = Event::make([
            'ticket_id' => 2,
            'evidence_id' => $evidence->id,
            'source' => 'email',
            'timestamp' => now(),
            'information' => 'Test event information',
        ]);

        $event->evidence()->associate($evidence);

        $this->assertEquals(
            $evidence,
            $event->evidence
        );
    }

    #[group('functional')]
    public function testModelFactory()
    {
        $event = Event::factory()->create();
        $eventFromDB = Event::where('source', $event->source)->first();
        $this->assertEquals($event->source, $eventFromDB->source);
    }
}
