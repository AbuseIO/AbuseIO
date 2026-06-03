<?php

namespace tests\Models;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class EvidenceTest extends TestCase
{

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

        $evidence->events->add($event);

        $this->assertTrue(
            $evidence->events->contains($event->id)
        );
    }
}
