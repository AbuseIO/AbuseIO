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
}

