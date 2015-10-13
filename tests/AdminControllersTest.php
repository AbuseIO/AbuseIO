<?php

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminControllersTest extends TestCase
{

    //use WithoutMiddleware;

    protected $_userId = 1; // use the default admin user defined in the db seed

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
        $user = User::find($this->_userId);
        $this->be($user);

        $response = $this->call('GET', '/admin/home');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminContacts()
    {
        $user = User::find($this->_userId);
        $this->be($user);

        $response = $this->call('GET', '/admin/contacts');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminNetblocks()
    {
        $user = User::find($this->_userId);
        $this->be($user);

        $response = $this->call('GET', '/admin/netblocks');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminDomains()
    {
        $user = User::find($this->_userId);
        $this->be($user);

        $response = $this->call('GET', '/admin/domains');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminTickets()
    {
        $user = User::find($this->_userId);
        $this->be($user);

        $response = $this->call('GET', '/admin/tickets');
        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminSearch()
    {
        $user = User::find($this->_userId);
        $this->be($user);

        // TODO mark, fix search forms
        // $response = $this->call('GET', '/admin/search');
        // $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testAdminAnalytics()
    {
        $user = User::find($this->_userId);
        $this->be($user);

        $response = $this->call('GET', '/admin/analytics');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
