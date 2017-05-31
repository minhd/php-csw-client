<?php

namespace MinhD\CSWClient\Unit\Request;

use MinhD\CSWClient\Request\DescribeRecord;
use MinhD\CSWClient\Utility\XML;

class DescribeRecordTest extends \PHPUnit_Framework_TestCase
{
    /** @test * */
    public function it_should_describe_record_correctly()
    {
        $request = new DescribeRecord();

        $params = [
            "service",
            "outputFormat",
            "schemaLanguage",
            "version"
        ];

        $sxml = XML::getSXML($request->getBody(), ['csw']);
        $describeRecord = $sxml->xpath('//csw:DescribeRecord');
        $this->assertEquals(1, count($describeRecord));
        $describeRecord = $describeRecord[0];
        $attributes = $describeRecord->attributes();
        foreach ($params as $key => $value) {
            $this->assertNotEmpty($attributes[$key]);
        }

        // <csw:TypeName>csw:Record</csw:TypeName>
    }

    /** @test * */
    public function it_should_describe_record_with_typenames()
    {
        $request = new DescribeRecord(['csw:Record']);
        $sxml = XML::getSXML($request->getBody(), ['csw']);
        $describeRecord = $sxml->xpath('//csw:DescribeRecord');
        $this->assertEquals(1, count($describeRecord));
        $typeNames = $sxml->xpath('//csw:TypeName');
        $this->assertEquals(1, count($typeNames));
        $typeName = $typeNames[0];
        $this->assertEquals("csw:Record", (string) $typeName);
    }
}
