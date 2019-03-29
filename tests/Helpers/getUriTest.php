<?php

namespace tests\Helpers;

use tests\TestCase;

class getUriTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getSet
     */
    public function it_should_test_get_uri($url, $expectedResult)
    {
        $this->assertEquals($expectedResult, getUri($url));
    }

    public static function getSet()
    {
        return [
            ['https://abuse.brad', false], // invalid because .brad is not a valid tld;/
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
