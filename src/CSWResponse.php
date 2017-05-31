<?php

namespace MinhD\CSWClient;

use Psr\Http\Message\ResponseInterface;
use Sabre\Xml\Service as XmlService;

class CSWResponse
{
    private $response;

    /**
     * CSWResponse constructor.
     * @param $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function httpCode()
    {
        return $this->response->getStatusCode();
    }

    public function asString()
    {
        return (string) $this->response->getBody();
    }

    /**
     * @return \SimpleXMLElement
     */
    public function asXML()
    {
        $sxml = new \SimpleXMLElement($this->asString());
        $sxml->registerXPathNamespace('csw', 'http://www.opengis.net/cat/csw/2.0.2');
        $sxml->registerXPathNamespace('gmd', 'http://www.isotc211.org/2005/gmd');

        return $sxml;
    }

    /**
     * @return \DOMDocument
     */
    public function asDOM()
    {
        $dom =new \DOMDocument();
        $dom->loadXML($this->asString());

        return $dom;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $service = new XmlService();
        return $service->parse($this->asString());
    }
}
