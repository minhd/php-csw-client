<?php


namespace MinhD\CSWClient\Request;


use DOMDocument;
use DOMXPath;
use MinhD\CSWClient\Utility\XML;
use Sabre\Xml\Writer;

class InsertRecord extends CSWRequest
{
    private $payload = null;
    private $namespaces = [];

    public function __construct($payload = null)
    {
        parent::__construct();

        $this->payload = $this->sanitize($payload);

        return $this->setBody($this->getBody());
    }

    public function sanitize($payload)
    {
        $doc = new DOMDocument;
        $doc->loadxml($payload);
        $context = $doc->documentElement;

        $xpath = new DOMXPath($doc);
        $namespaces = [];
        foreach( $xpath->query('namespace::*', $context) as $node ) {
            $namespaces[] = $node->nodeValue;
        }

        // all namespace
        $this->namespaces = $namespaces;

        // without <xml> tag
        return $xpath->document->saveHTML();
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

                $writer->startElement("{$cswNS}Insert");
                $writer->writeRaw($this->payload);
                $writer->endElement();
            }
        );

        return $doc;
    }
}