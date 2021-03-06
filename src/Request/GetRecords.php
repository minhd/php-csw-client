<?php


namespace MinhD\CSWClient\Request;

use MinhD\CSWClient\Utility\XML;
use Sabre\Xml\Writer;

class GetRecords extends CSWRequest
{
    private $defaultOptions = [
        'service' => 'CSW',
        'version' => '2.0.2',
        'startPosition' => '1',
        'maxRecords' => '15',
        'resultType' => "results",
        'outputFormat' => 'application/xml',
        'query' => [
            'ElementSetName' => 'full',
            'RawConstraints' => null
        ]
    ];

    private $defaultSchema = 'csw';

    private $options = [];
    public function __construct($options = [])
    {
        parent::__construct();

        $this->defaultOptions['outputSchema'] = XML::getNSURL($this->defaultSchema);
        $this->options = array_merge($this->defaultOptions, $options);

        return $this->setBody($this->getBody());
    }

    public function getBody()
    {
        $service = XML::CSWRequestWriter();

        $cswNS = '{http://www.opengis.net/cat/csw/2.0.2}';
        $options = $this->options;

        $doc = $service->write(
            "{$cswNS}GetRecords",
            function (Writer $writer) use ($cswNS, $options) {
                foreach ($options as $key => $value) {
                    if (is_array($value)) {
                        continue;
                    }
                    $writer->writeAttribute($key, $value);
                }

                $writer->startElement("{$cswNS}Query");
                $writer->writeAttribute("typeNames", 'csw:Record');

                $writer->writeElement("{$cswNS}ElementSetName", $options['query']['ElementSetName']);

                if (!array_key_exists('RawConstraints', $options['query']) || $options['query']['RawConstraints'] === null) {
                    $writer->endElement();
                    return;
                }

                //write RawConstraints
                $writer->writeRaw($options['query']['RawConstraints']);

                $writer->endElement();
            }
        );


        return $doc;
    }
}
