<?php

namespace AbuseIO\Http\Controllers;

use Zend\XmlRpc;
use Zend\XmlRpc\Server;
use Zend\XmlRpc\Client;

/**
 * Class ApiController
 * @package AbuseIO\Http\Controllers
 */
class ApiController extends Controller
{
    public function server()
    {
        // Set XmlWriter as XML Generator
        XmlRpc\AbstractValue::setGenerator(new XmlRpc\Generator\XmlWriter());

        $server = new Server();
        $server->setClass('AbuseIO\Http\Api\Incident', 'incident');
        echo $server->handle();
    }
/*
    public function client()
    {
        $client = new Client("http://localhost:8000/api");
        $httpClient = $client->getHttpClient();
        $httpClient->setAuth('admin@isp.local', 'ed54fcb1');
        echo $client->call('incident.update', array(1));
        dd($client);
    }
*/
}
