<?php


namespace MinhD\CSWClient\Request;

use GuzzleHttp\Psr7\Request;
use Sabre\Xml\Service;
use Sabre\Xml\Writer;

class GetCapabilities extends CSWRequest
{
    public function __construct()
    {
        parent::__construct();
        return $this->setBody($this->getBody());
    }

    public function getBody()
    {
        $service = new Service();

        $service->namespaceMap = [
            'http://www.opengis.net/cat/csw/2.0.2' => 'csw'
        ];

        $cswNS = '{http://www.opengis.net/cat/csw/2.0.2}';


        $doc = $service->write(
            "{$cswNS}GetCapabilities",
            function (Writer $writer) use ($cswNS) {
                $writer->namespaceMap = [
                    'http://www.opengis.net/ows' => 'ows',
                ];

                $owsNS = '{http://www.opengis.net/ows}';
                $writer->writeAttribute("service", "CSW");
                $writer->startElementNS("ows", "AcceptVersions", "http://www.opengis.net/ows");
                $writer->writeElement("{$owsNS}Version", "2.0.2");
                $writer->endElement();

                $writer->startElementNS("ows", "AcceptFormats", "http://www.opengis.net/ows");
                $writer->writeElement("{$owsNS}OutputFormat", "application/xml");
                $writer->endElement();
            }
        );

        return $doc;
    }
}
