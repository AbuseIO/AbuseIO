<?php

namespace tests\Models;

use AbuseIO\Models\Evidence;
use AbuseIO\Models\Event;

class EvidenceTest extends \TestCase
{
    /**
     * Testing the events() method on the model.
     */
    public function testHasManyEvents()
    {
        $this->assertTrue(
            Evidence::find(1)->events->contains(
                Event::find(1)->id
            )
        );
    }
}
