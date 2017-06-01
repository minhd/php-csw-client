<?php

namespace MinhD\CSWClient\Unit\Request;

use MinhD\CSWClient\Request\Harvest;
use MinhD\CSWClient\Utility\XML;

class HarvestTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_populate_the_right_field_with_resourceUrl()
    {
        $url = "http://dapds00.nci.org.au/thredds/wms/fx3/gbr1/gbr1_simple_2016-03-30.nc";
        $typeUrl = "http://www.opengis.net/wms";

        $request = new Harvest($url, $typeUrl);
        $sxml = XML::getSXML($request->getBody(), ['csw']);

        $this->assertEquals(1, count($sxml->xpath("//csw:Harvest")));
        $this->assertEquals(1, count($sxml->xpath("//csw:Source")));
        $this->assertEquals(1, count($sxml->xpath("//csw:ResourceType")));
        $content = (string) $sxml->xpath("//csw:Source")[0];
        $this->assertEquals($url, $content);
        $this->assertEquals($typeUrl, (string) $sxml->xpath("//csw:ResourceType")[0]);
    }

    /** @test **/
    public function it_should_populate_the_right_field_with_namespace()
    {
        $url = "http://dapds00.nci.org.au/thredds/wms/fx3/gbr1/gbr1_simple_2016-03-30.nc";
        $typeUrl = "http://www.opengis.net/wms";
        $type = "wms";

        $request = new Harvest($url, $type);
        $sxml = XML::getSXML($request->getBody(), ['csw']);
        $this->assertEquals(1, count($sxml->xpath("//csw:Harvest")));
        $this->assertEquals(1, count($sxml->xpath("//csw:Source")));
        $this->assertEquals(1, count($sxml->xpath("//csw:ResourceType")));
        $content = (string) $sxml->xpath("//csw:Source")[0];
        $this->assertEquals($url, $content);
        $this->assertEquals($typeUrl, (string) $sxml->xpath("//csw:ResourceType")[0]);
    }
}
