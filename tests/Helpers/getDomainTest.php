<?php

namespace tests\Helpers;

use PHPUnit\Framework\Attributes\DataProvider;
use tests\TestCase;

class getDomainTest extends TestCase
{
    #[DataProvider('getSet')]
    public function testGetDomain($domain, $expectedResult)
    {
        $this->assertEquals($expectedResult, getDomain($domain));
    }

    public static function getSet()
    {
        return [
            ['www.sobit.nl', true],
            ['abuse.io', true],
            ['abuse.io/?page=1', false], // a domain should not have a query string
            ['http://abuse.io', false], // a domain should not have a scheme
            ["abuse\n.io", true], // a domain in a stuctured text can have a carriage return;
        ];
    }
}
