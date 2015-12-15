<?php

class CastToBoolTest extends TestCase{

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
