<?php


namespace MinhD\CSWClient\Request;


use MinhD\CSWClient\Utility\XML;
use Sabre\Xml\Writer;

class DeleteRecords extends CSWRequest
{
    private $constraints = null;

    public function __construct($constraints)
    {
        parent::__construct();
        $this->constraints = $constraints;
        return $this->setBody($this->getBody());
    }

    public function getBody()
    {
        $service = XML::CSWRequestWriter();

        $cswNS = '{http://www.opengis.net/cat/csw/2.0.2}';
        $doc = $service->write(
            "{$cswNS}Transaction",
            function (Writer $writer) use ($cswNS) {
                $writer->writeAttribute("service", "CSW");
                $writer->writeAttribute("version", "2.0.2");

                // typeNames
                $writer->startElement("{$cswNS}Delete");
                $writer->writeRaw($this->constraints);
                $writer->endElement();
            }
        );

        return $doc;
    }
}