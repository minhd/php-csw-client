<?php


namespace MinhD\CSWClient\Utility;


class XML
{
    public static $namespaces = [
        'csw' => "http://www.opengis.net/cat/csw/2.0.2",
        "ows" => "http://www.opengis.net/ows",
        'gmd' => "http://www.isotc211.org/2005/gmd",
        "gmo" => "http://www.isotc211.org/2005/gmo",
        "gco" => "http://www.isotc211.org/2005/gco",
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
}