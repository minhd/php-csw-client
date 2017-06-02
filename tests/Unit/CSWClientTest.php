<?php

namespace MinhD\CSWClient\Unit;

use MinhD\CSWClient\CSWClient;
use MinhD\CSWClient\Exception\CSWException;

class CSWClientTest extends \PHPUnit_Framework_TestCase
{
    protected $url = "http://ecat.ga.gov.au/geonetwork/srv/eng/csw";

    /** @test */
    public function it_should_create_new_instance()
    {
        $actual = new CSWClient($this->url);
        $this->assertInstanceOf(CSWClient::class, $actual);
    }

    /** @test **/
    public function it_should_check_isAccessible_correctly()
    {
        $client = new CSWClient("http://unknown-url");
        $this->assertFalse($client->isAccessible());
    }

    /** @test **/
    public function it_should_check_isAccessible()
    {
        $client = new CSWClient($this->url);
        $this->assertTrue($client->isAccessible());
    }
}
