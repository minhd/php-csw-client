<?php

namespace MinhD\CSWClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
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
        return $this->send(new GetCapabilities());
    }

    /**
     * @param $id
     * @param array $options
     * @return CSWResponse
     */
    public function getRecordByID($id, $options = [])
    {
        return $this->send(new GetRecordById($id, $options));
    }

    /**
     * @param Request $request
     * @return CSWResponse
     */
    public function send(Request $request)
    {
        $result = $this->webClient->send($request);
        return new CSWResponse($result);
    }
}
