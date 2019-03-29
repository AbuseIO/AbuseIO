<?php

namespace tests\Helpers;

use tests\TestCase;

class getDomainTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getSet
     */
    public function it_should_test_getDomain($domain, $expectedResult)
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
