<?php

namespace MinhD\CSWClient\Feature;

use MinhD\CSWClient\CSWClient;
use MinhD\CSWClient\Request\CSWRequest;
use MinhD\CSWClient\Utility\XML;

class BasicCSWOperationTest extends \PHPUnit_Framework_TestCase
{
    protected $url = "http://ecat.ga.gov.au/geonetwork/srv/eng/csw";

    /** @var CSWClient */
    protected $client;

    /** @test */
    public function it_should_get_response_in_multiple_formats()
    {
        $result = $this->client->getCapabilities();

        $this->assertEquals(200, $result->httpCode());
        $this->assertNotEmpty($result->asString());
        $this->assertInstanceOf(\SimpleXMLElement::class, $result->asXML());
        $this->assertInstanceOf(\DOMDocument::class, $result->asDOM());
        $this->assertNotEmpty($result->asArray());
    }

    /** @test * */
    public function it_should_get_capabilities()
    {
        $result = $this->client->getCapabilities();

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
    public function it_should_get_record_by_id_gmd()
    {
        $result = $this->client->getRecordByID(
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

    /** @test * */
    public function it_should_get_record_by_id_default_settings_csw()
    {
        $result = $this->client->getRecordByID(
            "1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee"
        );

        $sxml = $result->asXML();
        $sxml = XML::getSXML($sxml, ['csw']);

        $this->assertEquals(
            1,
            count($sxml->xpath("//csw:GetRecordByIdResponse"))
        );
        $this->assertEquals(1, count($sxml->xpath("//csw:Record")));
    }

    /** @test **/
    public function it_should_get_all_records()
    {
        $result = $this->client->getRecords();
        $sxml = XML::getSXML($result->asXML(), ['csw']);

        $this->assertEquals(1, count($sxml->xpath('//csw:SearchResults')));

        $searchResult = $sxml->xpath('//csw:SearchResults')[0];
        $this->assertGreaterThan(1000, $searchResult['numberOfRecordsMatched']);
        $this->assertEquals(15, (int) $searchResult['numberOfRecordsReturned']);

        $this->assertEquals(15, count($sxml->xpath("//csw:Record")));
    }

    /** @test **/
    public function it_should_get_records_based_on_constraints()
    {
        $result = $this->client->getRecords([
            'service' => 'CSW',
            'outputSchema' => XML::getNSURL('csw'),
            'query' => [
                'ElementSetName' => 'full',
                'RawConstraints' => '<csw:Constraint version="1.1.0">
      <ogc:Filter>
        <ogc:PropertyIsEqualTo>
          <ogc:PropertyName>csw:title</ogc:PropertyName>
          <ogc:Literal>Gilbert</ogc:Literal>
        </ogc:PropertyIsEqualTo>
      </ogc:Filter>
    </csw:Constraint>'
            ]
        ]);

        $sxml = XML::getSXML($result->asXML(), ['csw', 'dc']);
        $this->assertEquals(1, count($sxml->xpath('//csw:SearchResults')));
        $searchResult = $sxml->xpath('//csw:SearchResults')[0];

        // must match some records
        $numMatch = (int) $searchResult['numberOfRecordsReturned'];
        $this->assertGreaterThan(0, $numMatch);

        // same set returned
        $this->assertEquals($numMatch, count($sxml->xpath("//csw:Record")));
        $this->assertEquals($numMatch, count($sxml->xpath("//csw:Record/dc:identifier")));
    }

    /** @test **/
    public function it_should_search_brief_record_in_a_bounding_box()
    {
        $result = $this->client->getRecords([
            'service' => 'CSW',
            'outputSchema' => XML::getNSURL('csw'),
            'query' => [
                'ElementSetName' => 'brief',
                'RawConstraints' => '<csw:Constraint version="1.1.0">
      <ogc:Filter>
        <ogc:BBOX>
          <ogc:PropertyName>ows:BoundingBox</ogc:PropertyName>
          <gml:Envelope>
            <gml:lowerCorner>47 -5</gml:lowerCorner>
            <gml:upperCorner>55 20</gml:upperCorner>
          </gml:Envelope>
        </ogc:BBOX>
      </ogc:Filter>
    </csw:Constraint>'
            ]
        ]);


        $sxml = XML::getSXML($result->asXML(), ['csw', 'dc']);
        $this->assertEquals(1, count($sxml->xpath('//csw:SearchResults')));
        $searchResult = $sxml->xpath('//csw:SearchResults')[0];

        // must match some records
        $numMatch = (int) $searchResult['numberOfRecordsReturned'];
        $this->assertGreaterThan(0, $numMatch);

        // same set returned
        $this->assertEquals($numMatch, count($sxml->xpath("//csw:BriefRecord")));
        $this->assertEquals($numMatch, count($sxml->xpath("//csw:BriefRecord/dc:identifier")));
    }

    /** @test **/
    public function it_should_describe_records()
    {
        $result = $this->client->describeRecord();
        $sxml = XML::getSXML($result->asXML(), ['csw', 'dc']);

        $this->assertEquals(1, count($sxml->xpath('//csw:DescribeRecordResponse')));
        $this->assertGreaterThanOrEqual(1, count($sxml->xpath('//csw:SchemaComponent')));
    }

    public function setUp()
    {
        $this->client = new CSWClient($this->url);
    }
}
