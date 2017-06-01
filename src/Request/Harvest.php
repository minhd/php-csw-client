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

        return $this->setBody($this->getBody());
    }

    public function getBody()
    {
        $service = XML::CSWRequestWriter();

        $cswNS = '{http://www.opengis.net/cat/csw/2.0.2}';
        $doc = $service->write(
            "{$cswNS}Harvest",
            function (Writer $writer) use ($cswNS) {
                $writer->writeElement("Source", $this->url);
                $writer->writeElement("ResourceType", $this->type);
            }
        );

        return $doc;
    }
}