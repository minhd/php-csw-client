<?php


namespace MinhD\CSWClient\Utility;

use Sabre\Xml\Service;

class XML
{
    public static $namespaces = [
        'csw' => "http://www.opengis.net/cat/csw/2.0.2",
        "ows" => "http://www.opengis.net/ows",
        "ogc" => "http://www.opengis.net/ogc",
        'gmd' => "http://www.isotc211.org/2005/gmd",
        "gmo" => "http://www.isotc211.org/2005/gmo",
        "gco" => "http://www.isotc211.org/2005/gco",
        "dc" => "http://purl.org/dc/elements/1.1/",
        "gml" => "http://www.opengis.net/gml",
        "wms" => "http://www.opengis.net/wms",
        "wfs" => "http://www.opengis.net/wfs"
    ];

    public static function getSXML($payload, $xpathNS = [])
    {
        $namespaces = static::$namespaces;

        $sxml = $payload;
        if (!($sxml instanceof \SimpleXMLElement)) {
            $sxml = new \SimpleXMLElement($sxml);
        }


        if ($xpathNS === "*") {
            $xpathNS = $namespaces;
        }

        foreach ($xpathNS as $ns) {
            if (!array_key_exists($ns, $namespaces)) {
                continue;
            }
            $sxml->registerXPathNamespace($ns, $namespaces[$ns]);
        }
        return $sxml;
    }

    public static function getNSURL($ns)
    {
        $namespaces = static::$namespaces;
        if (!array_key_exists($ns, $namespaces)) {
            return null;
        }

        return $namespaces[$ns];
    }

    public static function CSWRequestWriter()
    {
        $service = new Service();
//        $map = [];
//        foreach (self::$namespaces as $key => $value) {
//            $map[self::getNSURL($key)] = $value;
//        }
        $service->namespaceMap = [
            XML::getNSURL('csw') => 'csw',
            XML::getNSURL('ogc') => 'ogc',
            XML::getNSURL('gml') => 'gml',
            XML::getNSURL('gmd') => 'gmd'
        ];

        return $service;
    }


}
