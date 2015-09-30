<?php

class AshControllersTest extends TestCase
{

    /**
     * @return void
     */
    public function testAshCollectOne()
    {
        $response = $this->call('GET', '/ash/collect/1/6bb1aef09ea536260e3afe3fb9b432e4');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAshCollectTwo()
    {
        $response = $this->call('GET', '/ash/collect/2/92d74aa22a225708cc9092340b3b79be');
        $this->assertEquals(200, $response->getStatusCode());
    }

}
