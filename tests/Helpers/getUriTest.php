<?php

namespace tests\Helpers;

use PHPUnit\Framework\Attributes\DataProvider;
use tests\TestCase;

class getUriTest extends TestCase
{
    #[DataProvider('getSet')]
    public function testGetUri($url, $expectedResult)
    {
        $this->assertEquals($expectedResult, getUri($url));
    }

    public static function getSet()
    {
        return [
            ['https://abuse.brad', '/'], // allow unknown TLDs; helper returns normalized path
            ["http://\nabuse.io", '/'], // it is ok if a uri has carriage return;
            ['http://abuse.io/index.php?page=1', '/index.php?page=1'],
            ['http://www.abuse.io', '/'],
            ['ftp://abuse.io', '/'],
            ['abuse.io', false], // a uri should have a scheme.
            ['http://www.abuse.io', '/'],
            ['http://www.abuse.io/page/1', '/page/1'],

        ];
    }
}
