<?php


namespace MinhD\CSWCLient\Request;

use GuzzleHttp\Psr7\Request;
use Sabre\Xml\Service;
use Sabre\Xml\Writer;

class GetRecordById extends Request
{
    private $headers = [
        'Content-Type' => 'application/xml',
        'Accept' => 'application/xml'
    ];

    private $defaultOptions = [
        'outputSchema' => "http://www.opengis.net/cat/csw/2.0.2",
        'ElementSetName' => 'full'
    ];

    private $id;
    private $options;

    public function __construct($id, $options = [])
    {
        $this->id = $id;
        $this->options = array_merge($this->defaultOptions, $options);
        return parent::__construct('POST', '', $this->headers, $this->getBody());
    }

    public function getBody()
    {
        $service = new Service();

        $service->namespaceMap = [
            'http://www.opengis.net/cat/csw/2.0.2' => 'csw',
        ];

        $options = $this->options;

        $ns = '{http://www.opengis.net/cat/csw/2.0.2}';
        $doc = $service->write(
            "{$ns}GetRecordById",
            function(Writer $writer) use ($ns, $options) {
                $writer->writeAttribute('outputSchema', $options['outputSchema']);
                $writer->write([
                    "{$ns}Id" => $this->id,
                    "{$ns}ElementSetName" => $this->options['ElementSetName']
                ]);
            }
        );

        return $doc;
    }
}