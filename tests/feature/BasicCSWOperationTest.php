<?php

namespace MinhD\CSWClient;

class BasicCSWOperationTest extends \PHPUnit_Framework_TestCase
{
    protected $url = "http://ecat.ga.gov.au/geonetwork/srv/eng/csw";

    /** @test */
    public function it_should_get_capabilities()
    {
        $client = new CSWClient($this->url);
        $result = $client->getCapabilities();
        $this->assertEquals(200, $result->httpCode());
        $this->assertNotEmpty($result->asString());
    }
}
