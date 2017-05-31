<?php

namespace MinhD\CSWClient\Unit\Request;

use MinhD\CSWClient\Request\GetRecords;
use MinhD\CSWClient\Utility\XML;

class GetRecordsTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_construct_body()
    {
        $request = new GetRecords();
        $sxml = XML::getSXML($request->getBody(), ['csw']);

        $params = [
            'service',
            'version',
            'startPosition',
            'maxRecords',
            'resultType',
            'outputFormat'
        ];

        $this->assertEquals(1, count($sxml->xpath("//csw:GetRecords")));
        $elem = $sxml->xpath('//csw:GetRecords')[0];
        foreach ($params as $param) {
            $this->assertNotEmpty($elem[$param]);
        }

        $this->assertEquals(1, count($sxml->xpath("//csw:GetRecords/csw:Query")));
        $this->assertEquals(1, count($sxml->xpath("//csw:GetRecords/csw:Query/csw:ElementSetName")));
    }

    /** @test **/
    public function it_should_construct_body_as_options()
    {
        $expected = [
            'service' => 'CSW',
            'startPosition' => 17,
            'outputSchema' => XML::getNSURL('gmd'),
            'query' => [
                'ElementSetName' => 'custom'
            ]
        ];

        $request = new GetRecords($expected);
        $sxml = XML::getSXML($request->getBody(), ['csw']);

        $this->assertEquals(1, count($sxml->xpath("//csw:GetRecords")));
        $elem = $sxml->xpath('//csw:GetRecords')[0];
        foreach ($expected as $key=>$value) {
            if (is_array($value)) {
                continue;
            }
            $this->assertEquals((string) $elem[$key], $value);
        }

        $this->assertEquals(1, count($sxml->xpath("//csw:GetRecords/csw:Query")));
        $this->assertEquals(1, count($sxml->xpath("//csw:GetRecords/csw:Query/csw:ElementSetName")));
        $elem = $sxml->xpath("//csw:GetRecords/csw:Query/csw:ElementSetName")[0];
        $this->assertEquals((string) $elem, $expected['query']['ElementSetName']);
    }
}
