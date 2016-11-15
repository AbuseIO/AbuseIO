<?php

namespace tests\Helpers;

use tests\TestCase;

class generateGuidTest extends TestCase
{
    public function testLength()
    {
        $this->assertEquals(36, strlen(generateGuid()));
    }

/**
 * example of a guid 10C7EDE4-60D2-30A4-BE01-E4CDAB1BF042
 */

    public function testFormat()
    {
       $this->assertTrue(
           !!preg_match(
               '/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}/',
               generateGuid()
           )
       );
    }
}
