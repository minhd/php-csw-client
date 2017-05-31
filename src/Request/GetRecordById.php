<?php


namespace MinhD\CSWClient\Request;

use GuzzleHttp\Psr7\Request;
use Sabre\Xml\Service;
use Sabre\Xml\Writer;

class GetRecordById extends CSWRequest
{
    private $defaultOptions = [
        'outputSchema' => "http://www.opengis.net/cat/csw/2.0.2",
        'ElementSetName' => 'full'
    ];

    private $id;
    private $options;

    public function __construct($id, $options = [])
    {
        parent::__construct();

        $this->id = $id;
        $this->options = array_merge($this->defaultOptions, $options);

        return $this->setBody($this->getBody());
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
            function (Writer $writer) use ($ns, $options) {
                $writer->writeAttribute('outputSchema', $options['outputSchema']);
                $writer->writeAttribute('service', "CSW");
                $writer->writeAttribute('version', "2.0.2");
                $writer->write([
                    "{$ns}Id" => $this->id,
                    "{$ns}ElementSetName" => $this->options['ElementSetName']
                ]);
            }
        );

        return $doc;
    }
}
