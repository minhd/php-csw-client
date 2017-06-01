<?php

namespace MinhD\CSWClient\Unit\Request;

use MinhD\CSWClient\Exception\CSWException;
use MinhD\CSWClient\Request\InsertRecord;
use MinhD\CSWClient\Utility\XML;

class InsertRecordTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_insert_the_raw_metadata()
    {
        $request = new InsertRecord("<record>some data</record>");
        $sxml = XML::getSXML($request->getBody(), ['csw']);

        $this->assertEquals(1, count($sxml->xpath("//csw:Transaction")));
        $this->assertEquals(1, count($sxml->xpath("//csw:Insert")));
        $content = (string) $sxml->xpath("//csw:Insert/record")[0];
        $this->assertEquals("some data", $content);
    }
}
