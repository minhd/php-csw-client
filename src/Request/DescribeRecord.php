<?php


namespace MinhD\CSWClient\Request;

use MinhD\CSWClient\Utility\XML;
use Sabre\Xml\Writer;

class DescribeRecord extends CSWRequest
{
    protected $params = [
        "service" => "CSW",
        "version" => "2.0.2",
        "outputFormat" => "application/xml",
        "schemaLanguage" => "http://www.w3.org/XML/Schema"
    ];

    private $typeNames = [];

    public function __construct($typeNames = [])
    {
        parent::__construct();
        $this->typeNames = $typeNames;
        return $this->setBody($this->getBody());
    }

    public function getBody()
    {
        $service = XML::CSWRequestWriter();

        $cswNS = '{http://www.opengis.net/cat/csw/2.0.2}';
        $params = $this->params;
        $typeNames = $this->typeNames;
        $doc = $service->write(
            "{$cswNS}DescribeRecord",
            function (Writer $writer) use ($cswNS, $params, $typeNames) {
                foreach ($params as $key => $value) {
                    $writer->writeAttribute($key, $value);
                }
                if (count($typeNames) == 0) {
                    return;
                }

                // typeNames
                foreach ($this->typeNames as $typeName) {
                    $writer->writeElement("{$cswNS}TypeName", $typeName);
                }

            }
        );

        return $doc;
    }
}