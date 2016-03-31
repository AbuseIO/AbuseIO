<?php

namespace tests\Helpers;

use tests\TestCase;

class castStringToBoolTest extends TestCase
{
    public function testStringTrueIsTrue()
    {
        $this->assertTrue(castStringToBool('true'));
    }

    public function testStringFalseIsFalse()
    {
        $this->assertFalse(castStringToBool('false'));
    }

    public function testAnyStringIsFalse()
    {
        $this->assertFalse(castStringToBool('anyString'));
    }
}
