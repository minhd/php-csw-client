<?php


namespace MinhD\CSWClient\Request;


use MinhD\CSWClient\Utility\XML;
use Sabre\Xml\Writer;

class Harvest extends CSWRequest
{
    private $url = null;
    private $type = null;

    public function __construct($url, $type)
    {
        parent::__construct();

        $this->url = $url;
        $this->type = $type;

        // mapping from namespace to url
        if (array_key_exists($type, XML::$namespaces)) {
            $this->type = XML::getNSURL($type);
        }

        return $this->setBody($this->getBody());
    }

    public function getBody()
    {
        $service = XML::CSWRequestWriter();

        $cswNS = '{http://www.opengis.net/cat/csw/2.0.2}';
        $doc = $service->write(
            "{$cswNS}Harvest",
            function (Writer $writer) use ($cswNS) {
                $writer->writeAttribute("service", "CSW");
                $writer->writeAttribute("version", "2.0.2");
                $writer->writeElement("{$cswNS}Source", $this->url);
                $writer->writeElement("{$cswNS}ResourceType", $this->type);
            }
        );

        return $doc;
    }
}