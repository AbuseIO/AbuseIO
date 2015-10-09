<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminControllersTest extends TestCase
{

    use WithoutMiddleware;

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

        $response = $this->call('GET', '/admin/home');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminContacts()
    {
        $response = $this->call('GET', '/admin/contacts');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminNetblocks()
    {
        $response = $this->call('GET', '/admin/netblocks');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminDomains()
    {
        $response = $this->call('GET', '/admin/domains');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminTickets()
    {
        $response = $this->call('GET', '/admin/tickets');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminSearch()
    {
        // TODO mark, fix search forms
        // $response = $this->call('GET', '/admin/search');
        // $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminAnalytics()
    {
        $response = $this->call('GET', '/admin/analytics');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
