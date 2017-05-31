<?php

namespace MinhD\CSWClient\Feature;

use MinhD\CSWClient\CSWClient;
use MinhD\CSWClient\Utility\XML;

class BasicCSWOperationTest extends \PHPUnit_Framework_TestCase
{
    protected $url = "http://ecat.ga.gov.au/geonetwork/srv/eng/csw";

    /** @test */
    public function it_should_get_response_in_multiple_formats()
    {
        $client = new CSWClient($this->url);
        $result = $client->getCapabilities();

        $this->assertEquals(200, $result->httpCode());
        $this->assertNotEmpty($result->asString());
        $this->assertInstanceOf(\SimpleXMLElement::class, $result->asXML());
        $this->assertInstanceOf(\DOMDocument::class, $result->asDOM());
        $this->assertNotEmpty($result->asArray());
    }

    /** @test * */
    public function it_should_get_capabilities()
    {
        $client = new CSWClient($this->url);
        $result = $client->getCapabilities();
        $this->assertNotEmpty($result->asString());

        $sxml = $result->asXML();
        $sxml->registerXPathNamespace(
            "csw",
            "http://www.opengis.net/cat/csw/2.0.2"
        );
        $sxml->registerXPathNamespace("ows", "http://www.opengis.net/ows");

        $this->assertGreaterThan(1, count($sxml->xpath('//ows:Keyword')));
        $this->assertEquals(1, count($sxml->xpath('//ows:ProviderName')));
        $this->assertEquals(
            "Geoscience Australia",
            (string)$sxml->xpath('//ows:ProviderName')[0]
        );
    }

    /** @test * */
    public function it_should_get_record_by_id()
    {
        $client = new CSWClient($this->url);
        $result = $client->getRecordByID(
            "1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee",
            [
                "outputSchema" => "http://www.isotc211.org/2005/gmd",
                "elementSetName" => "full"
            ]
        );

        $sxml = $result->asXML();
        $sxml = XML::getSXML($sxml, ['csw', 'gmd', 'gmo', 'gco']);

        $this->assertEquals(
            1,
            count($sxml->xpath("//csw:GetRecordByIdResponse"))
        );
        $this->assertEquals(1, count($sxml->xpath("//gmd:MD_Metadata")));
        $this->assertEquals(
            1,
            count($sxml->xpath("//gmd:fileIdentifier/gco:CharacterString"))
        );

        $this->assertEquals(
            "1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee",
            (string)$sxml->xpath("//gmd:fileIdentifier/gco:CharacterString")[0]
        );
    }
}
