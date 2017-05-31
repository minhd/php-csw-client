<?php

namespace MinhD\CSWClient;

use GuzzleHttp\Client;

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
        $result = $this->webClient->request('POST', '', [
            'headers' => [
                'Content-Type' => 'application/xml',
                'Accept' => 'application/xml'
            ],
            'body' => '<?xml version="1.0" encoding="UTF-8"?>
<csw:GetCapabilities service="CSW" xmlns:csw="http://www.opengis.net/cat/csw/2.0.2">
    <ows:AcceptVersions xmlns:ows="http://www.opengis.net/ows">
        <ows:Version>2.0.2</ows:Version>
    </ows:AcceptVersions>
    <ows:AcceptFormats xmlns:ows="http://www.opengis.net/ows">
        <ows:OutputFormat>application/xml</ows:OutputFormat>
    </ows:AcceptFormats>
</csw:GetCapabilities>
            ']);
        return new CSWResponse($result);
    }

    public function getRecordByID($id, $options)
    {
        $request = new Request\GetRecordById();
        $result = $this->webClient->request('POST', '', [
            'headers' => [
                'Content-Type' => 'application/xml',
                'Accept' => 'application/xml'
            ],
            'body' => '<?xml version="1.0" encoding="UTF-8"?>
<csw:GetRecordById xmlns:csw="http://www.opengis.net/cat/csw/2.0.2" service="CSW" version="2.0.2" outputSchema="http://www.isotc211.org/2005/gmd">
  <csw:Id>'.$id.'</csw:Id>
  <csw:ElementSetName>full</csw:ElementSetName>
</csw:GetRecordById>
            ']);
        return new CSWResponse($result);
    }
}
