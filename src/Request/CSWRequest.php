<?php


namespace MinhD\CSWClient\Request;

use GuzzleHttp\Psr7\Request;

class CSWRequest extends Request
{
    private $headers = [
        'Content-Type' => 'application/xml',
        'Accept' => 'application/xml'
    ];

    /**
     * CSWRequest constructor.
     */
    public function __construct()
    {
        return parent::__construct('POST', '', $this->headers, '');
    }

    public function setBody($body)
    {
        return parent::__construct('POST', '', $this->headers, $body);
    }
}
