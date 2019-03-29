<?php

namespace tests;

//use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminControllersTest extends TestCase
{
    /*
     * This is required else it would generate:
     *  Fatal error:  Maximum function nesting level of '100' reached, aborting
     */
//    use WithoutMiddleware;

    /**
     * @return void
     */
    public function testAdminRedirect()
    {
        $response = $this->call('GET', '/admin/');
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAdminHome()
    {
        $this->be($this->user);
        $response = $this->call('GET', '/admin/home');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAdminContacts()
    {
        $this->be($this->user);
        $response = $this->call('GET', '/admin/contacts');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAdminNetblocks()
    {
        $this->be($this->user);
        $response = $this->call('GET', '/admin/netblocks');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAdminDomains()
    {
        $this->be($this->user);
        $response = $this->call('GET', '/admin/domains');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAdminTickets()
    {
        $this->be($this->user);
        $response = $this->call('GET', '/admin/tickets');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAdminAnalytics()
    {
        $this->be($this->user);
        $response = $this->call('GET', '/admin/analytics');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
