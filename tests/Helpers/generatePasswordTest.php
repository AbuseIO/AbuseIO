<?php

namespace tests\Helpers;

use tests\TestCase;

class generatePasswordTest extends TestCase
{
    public function testMd5()
    {
        $this->assertMatchesRegularExpression('/^[a-z0-9]{8}$/', generatePassword());
    }
}
