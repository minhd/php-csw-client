<?php


namespace MinhD\CSWClient\Feature;


use MinhD\CSWClient\CSWClient;
use MinhD\CSWClient\Exception\CSWException;
use MinhD\CSWClient\Utility\XML;
use Symfony\Component\Config\Definition\Exception\Exception;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    /** @var CSWClient */
    protected $client;

    protected $url = "http://localhost:8000";

    protected $dataPath = "";

    /** @test **/
    public function it_should_throw_exception_when_wrongly_insert_record()
    {
        $file = 'Record_4fc1a4a7-297c-4b39-b479-fead57c6ba8d_gmd_not_well_formed.xml';
        $xml = file_get_contents($this->dataPath.$file);
        $this->expectException(CSWException::class);
        $this->client->insertRecord($xml);
    }

    /** @test **/
    public function it_should_insert_record_correctly()
    {
        $this->client->deleteRecord("4fc1a4a7-297c-4b39-b479-fead57c6ba8d");

        try {
            $file = 'Record_4fc1a4a7-297c-4b39-b479-fead57c6ba8d_gmd.xml';
            $xml = file_get_contents($this->dataPath.$file);
            $result = $this->client->insertRecord($xml);
            $sxml = XML::getSXML($result->asXML());
            $totalInserted = (int) $sxml->xpath('//csw:totalInserted')[0];
            $this->assertEquals(1, $totalInserted);
            $identifier = (string) $sxml->xpath('//dc:identifier')[0];
            $this->assertEquals($identifier, "4fc1a4a7-297c-4b39-b479-fead57c6ba8d");
        } catch (CSWException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        // clean up, delete the record
        $this->client->deleteRecord("4fc1a4a7-297c-4b39-b479-fead57c6ba8d");
    }

    /** @test **/
    public function it_should_delete_record_correctly()
    {
        // insert it first
        try {
            $file = 'Record_4fc1a4a7-297c-4b39-b479-fead57c6ba8d_gmd.xml';
            $xml = file_get_contents($this->dataPath.$file);
            $this->client->insertRecord($xml);
        } catch (CSWException $e) {
            // it's fine if the record is already there
            $this->markAsRisky();
        }

        // delete it
        $result = $this->client->deleteRecord("4fc1a4a7-297c-4b39-b479-fead57c6ba8d");
        $sxml = XML::getSXML($result->asXML());
        $totalDeleted = (int) $sxml->xpath('//csw:totalDeleted')[0];
        $this->assertEquals(1, $totalDeleted);
    }

    /** @test **/
    public function it_should_check_exists()
    {
        // insert it first
        try {
            $file = 'Record_4fc1a4a7-297c-4b39-b479-fead57c6ba8d_gmd.xml';
            $xml = file_get_contents($this->dataPath.$file);
            $this->client->insertRecord($xml);
        } catch (CSWException $e) {
            // it's fine if the record is already there
            $this->markAsRisky();
        }

        $this->assertTrue($this->client->hasRecord("4fc1a4a7-297c-4b39-b479-fead57c6ba8d"));
    }

    /** @test **/
    public function it_should_check_not_exists()
    {
        $this->assertFalse($this->client->hasRecord("not-existence_record"));
    }

    /** @test **/
    public function it_should_harvest_from_wfs()
    {
        $wfsUrl = "http://www.environment.gov.au/mapping/services/ogc_services/World_Heritage_Areas/MapServer/WFSServer";

        $result = $this->client->harvest($wfsUrl, 'wfs');

        $sxml = XML::getSXML($result->asXML());
        $totalInserted = (int) $sxml->xpath('//csw:totalInserted')[0];
        $this->assertGreaterThanOrEqual(1, $totalInserted);
    }

    /** @test **/
    public function it_should_harvest_from_wms()
    {
        $wfsUrl = "http://www.environment.gov.au/mapping/services/ogc_services/World_Heritage_Areas/MapServer/WMSServer";

        $result = $this->client->harvest($wfsUrl, 'wms');

        $sxml = XML::getSXML($result->asXML());
        $totalInserted = (int) $sxml->xpath('//csw:totalInserted')[0];
        $this->assertGreaterThanOrEqual(1, $totalInserted);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->dataPath = dirname(__FILE__) . './../../resources/test_records/';

        // check if localhost:8000 service is up and running
        $this->client = new CSWClient($this->url);

        if (!$this->client->isAccessible()) {
            $this->markTestSkipped("{$this->url} is not accessible. Skipping tests");
        }

        $result = $this->client->getCapabilities();
        if ($result->httpCode() != 200) {
            $this->markTestSkipped("No local service running");
        }
    }
}