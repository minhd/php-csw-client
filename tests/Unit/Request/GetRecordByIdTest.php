<?php

namespace MinhD\CSWCLient\Unit\Request;

use GuzzleHttp\Psr7\Request;
use MinhD\CSWClient\Request\GetRecordById;

class GetRecordByIdTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_construct_body_correctly_default()
    {
        $request = new GetRecordById('id');
        $this->assertEquals('<?xml version="1.0"?>
<csw:GetRecordById xmlns:csw="http://www.opengis.net/cat/csw/2.0.2" outputSchema="http://www.opengis.net/cat/csw/2.0.2">
 <csw:Id>id</csw:Id>
 <csw:ElementSetName>full</csw:ElementSetName>
</csw:GetRecordById>', trim($request->getBody()));
    }

    /** @test **/
    public function it_should_construct_body_custom_correctly()
    {
        $request = new GetRecordById('1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee', [
            'outputSchema' => 'http://www.isotc211.org/2005/gmd',
            'ElementSetName' => 'custom'
        ]);

        $this->assertEquals('<?xml version="1.0"?>
<csw:GetRecordById xmlns:csw="http://www.opengis.net/cat/csw/2.0.2" outputSchema="http://www.isotc211.org/2005/gmd">
 <csw:Id>1c2403c3-44f6-4421-ba7f-dc48a1c5e5ee</csw:Id>
 <csw:ElementSetName>custom</csw:ElementSetName>
</csw:GetRecordById>', trim($request->getBody()));
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
