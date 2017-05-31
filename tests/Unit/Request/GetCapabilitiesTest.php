<?php

namespace MinhD\CSWClient\Unit\Request;

use GuzzleHttp\Psr7\Request;
use MinhD\CSWClient\Request\GetCapabilities;

class GetCapabilitiesTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_construct_body_correctly()
    {
        $request = new GetCapabilities();

        $body = $request->getBody();

        $sxml = new \SimpleXMLElement($body);
        $sxml->registerXPathNamespace("csw", "http://www.opengis.net/cat/csw/2.0.2");
        $sxml->registerXPathNamespace("ows", "http://www.opengis.net/ows");

        $this->assertEquals(1, count($sxml->xpath("//csw:GetCapabilities")));
        foreach ($sxml->xpath("//csw:GetCapabilities") as $elem) {
            $this->assertEquals("CSW", $elem["service"]);
        }

        $this->assertEquals(1, count($sxml->xpath("//ows:AcceptVersions")));
        $this->assertEquals(1, count($sxml->xpath("//ows:AcceptVersions/ows:Version")));
        $this->assertEquals(1, count($sxml->xpath("//ows:AcceptFormats")));
        $this->assertEquals(1, count($sxml->xpath("//ows:AcceptFormats/ows:OutputFormat")));

        foreach ($sxml->xpath("//ows:AcceptVersions/ows:Version") as $elem) {
            $this->assertEquals("2.0.2", (string) $elem);
        }
    }

    /** @test **/
    public function it_should_be_a_request_object()
    {
        $request = new GetCapabilities();
        $this->assertInstanceOf(Request::class, $request);
    }
}
