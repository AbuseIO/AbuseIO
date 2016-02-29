<?php
namespace tests\Helpers;

use tests\TestCase;

class CastStringToBoolTest extends TestCase{

    public function testStringTrueIsTrue()
    {
        $this->assertTrue(castStringToBool("true"));
    }

    public function testStringFalseIsFalse()
    {
        $this->assertFalse(castStringToBool("false"));
    }

    public function testAnyStringIsFalse()
    {
        $this->assertFalse(castStringToBool("anyString"));
    }

}
