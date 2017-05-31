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

    /** @test **/
    public function it_should_construct_body_with_raw_contraints()
    {
        $expected = [
            'service' => 'CSW',
            'startPosition' => 1,
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
        ];

        $request = new GetRecords($expected);
        $sxml = XML::getSXML($request->getBody(), ['csw', 'ogc']);

        $this->assertEquals(1, count($sxml->xpath('//ogc:Literal')));
        $this->assertEquals(1, count($sxml->xpath('//ogc:PropertyName')));
        $this->assertEquals(1, count($sxml->xpath('//ogc:PropertyIsEqualTo')));
    }
}
