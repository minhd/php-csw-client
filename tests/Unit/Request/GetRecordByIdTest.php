<?php

namespace MinhD\CSWCLient\Unit\Request;

use GuzzleHttp\Psr7\Request;
use MinhD\CSWClient\Request\GetRecordById;
use MinhD\CSWClient\Utility\XML;

class GetRecordByIdTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_construct_body_correctly_default()
    {
        $request = new GetRecordById('id');

        $sxml = XML::getSXML($request->getBody(), ['csw']);
        $this->assertEquals(
            XML::getNSURL('csw'),
            (string) $sxml->xpath("//csw:GetRecordById")[0]['outputSchema']
        );

        $this->assertEquals('id', (string) $sxml->xpath("//csw:Id")[0]);
        $this->assertEquals('full', (string) $sxml->xpath("//csw:ElementSetName")[0]);
    }

    /** @test **/
    public function it_should_construct_body_custom_correctly()
    {
        $request = new GetRecordById('1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee', [
            'outputSchema' => XML::getNSURL('gmd'),
            'ElementSetName' => 'custom'
        ]);

        $sxml = XML::getSXML($request->getBody(), ['csw']);
        $this->assertEquals(
            XML::getNSURL('gmd'),
            (string) $sxml->xpath("//csw:GetRecordById")[0]['outputSchema']
        );

        $this->assertEquals('1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee', (string) $sxml->xpath("//csw:Id")[0]);
        $this->assertEquals('custom', (string) $sxml->xpath("//csw:ElementSetName")[0]);
    }

    /** @test **/
    public function it_should_return_a_good_request_object()
    {
        $request = new GetRecordById('1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee', [
            'outputSchema' => 'http://www.isotc211.org/2005/gmd',
            'ElementSetName' => 'custom'
        ]);
        $this->assertInstanceOf(Request::class, $request);
    }
}
