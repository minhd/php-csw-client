<?php

namespace MinhD\CSWClient;

use GuzzleHttp\Client;
use MinhD\CSWClient\Request\GetCapabilities;
use MinhD\CSWClient\Request\GetRecordById;

class CSWClient
{
    private $url;
    private $webClient;

    /**
     * CSWClient constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->webClient = new Client([
            'base_uri' => $this->url,
            'timeout'  => 5.0,
        ]);
    }

    /**
     * @return CSWResponse
     */
    public function getCapabilities()
    {
        $request = new GetCapabilities();
        $result = $this->webClient->send($request);
        return new CSWResponse($result);
    }

    public function getRecordByID($id, $options = [])
    {
        $request = new GetRecordById($id, $options);
        $result = $this->webClient->send($request);
        return new CSWResponse($result);
    }
}
