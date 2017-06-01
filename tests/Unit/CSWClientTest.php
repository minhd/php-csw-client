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
        $this->assertInstanceOf('\MinhD\CSWClient\CSWClient', $actual);
        $this->assertInstanceOf(CSWClient::class, $actual);
    }
}
