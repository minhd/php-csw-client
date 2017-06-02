<?php


namespace MinhD\CSWClient\Unit\Utility;


use MinhD\CSWClient\Utility\XML;

class XMLTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_get_ns_url()
    {
        $url = XML::getNSURL('csw');
        $this->assertEquals("http://www.opengis.net/cat/csw/2.0.2", $url);
    }

    /** @test **/
    public function it_should_not_get_undefined_ns_url()
    {
        $url = XML::getNSURL('asdf');
        $this->assertNull($url);
    }

    /** @test **/
    public function it_should_get_sxml_with_namespace_for_xpath()
    {
        $dataPath = dirname(__FILE__) . './../../../resources/test_records/';
        $file = 'Record_4fc1a4a7-297c-4b39-b479-fead57c6ba8d_gmd.xml';
        $xml = file_get_contents($dataPath.$file);

        $sxml = XML::getSXML($xml, ['gco', 'gmd']);
        $this->assertEquals(1, count($sxml->xpath('//gmd:fileIdentifier/gco:CharacterString')));
    }

    /** @test **/
    public function it_should_not_fail_when_provided_with_unknown_namespace()
    {
        $dataPath = dirname(__FILE__) . './../../../resources/test_records/';
        $file = 'Record_4fc1a4a7-297c-4b39-b479-fead57c6ba8d_gmd.xml';
        $xml = file_get_contents($dataPath.$file);

        $sxml = XML::getSXML($xml, ['xsf']);
        $this->assertEquals(1, count($sxml->xpath('//gmd:fileIdentifier/gco:CharacterString')));
    }
}