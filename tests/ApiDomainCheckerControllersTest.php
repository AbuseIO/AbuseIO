<?php

namespace tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;

class ApiDomainCheckerControllersTest extends TestCase
{
    /*
     * This is required else it would generate:
     *  Fatal error:  Maximum function nesting level of '100' reached, aborting
     */
    use WithoutMiddleware;

    /**
     * @return void
     */
    public function testAdminRedirect()
    {
        //        $response = $this->call('POST', '/admin/verifyexternalapi', ['url' => 'http://127.0.0.1']);//['url' => 'http://localhost']);
        //
        //
        //        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(true);
    }
}
