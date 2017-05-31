<?php

namespace MinhD\CSWClient\Feature;

use MinhD\CSWClient\CSWClient;

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

    /** @test **/
    public function it_should_get_record_by_id()
    {
//        $client = new CSWClient($this->url);
//        $result = $client->getRecordByID("1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee", [
//            "outputSchema" => "http://www.isotc211.org/2005/gmd",
//            "elementSetName" => "full"
//        ]);
//
//        $xml = $result->asXML();
//        $gmd = $xml->xpath("gmd:MD_Metadata")[0]->asXML();
//        $this->assertNotEmpty($gmd);

//        $result = $client->getRecordByID("1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee", [
//            "outputSchema" => "http://www.opengis.net/cat/csw/2.0.2",
//            "elementSetName" => "full"
//        ]);
//        $xml = $result->asXML();
//        dd($xml);
//
//        $csw = $xml->xpath("csw:Record");
//        dd($csw);
//
//        dd($result->asXML());
    }
}
