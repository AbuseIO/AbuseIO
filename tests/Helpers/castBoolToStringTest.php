<?php

namespace tests\Helpers;

use tests\TestCase;

class castBoolToStringTest extends TestCase
{
    public function testTrueIsStringTrue()
    {
        $this->assertEquals(castBoolToString(true), 'true');
    }

    public function testFalseIsStringFalse()
    {
        $this->assertEquals(castBoolToString(false), 'false');
    }

    public function testAnyStringIsStringFalse()
    {
        $this->assertEquals(castBoolToString('anyString'), 'false');
    }
}
