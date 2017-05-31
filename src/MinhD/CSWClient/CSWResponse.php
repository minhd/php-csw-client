<?php

namespace MinhD\CSWClient;

use Psr\Http\Message\ResponseInterface;

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
        return $this->response->getBody()->getContents();
    }

    public function asSXML()
    {
        return new \SimpleXMLElement($this->asString());
    }

    public function asDOM()
    {
        $dom = new \DOMDocument();
        return $dom->loadXML($this->asString());
    }

}