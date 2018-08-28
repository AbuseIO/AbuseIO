<?php

namespace tests;

class AshControllersTest extends TestCase
{
    /**
     * @return void
     */
    public function testAshCollectOne()
    {
        $response = $this->call('GET', '/ash/collect/1/8237675c392d59c64dee7bcee2fda785');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAshCollectTwo()
    {
        $response = $this->call('GET', '/ash/collect/2/c8a1f3942226f9a0464c60abc18445a7');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
