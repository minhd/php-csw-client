<?php

namespace MinhD\CSWClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use MinhD\CSWClient\Exception\CSWException;
use MinhD\CSWClient\Request\DeleteRecord;
use MinhD\CSWClient\Request\DeleteRecords;
use MinhD\CSWClient\Request\DescribeRecord;
use MinhD\CSWClient\Request\GetCapabilities;
use MinhD\CSWClient\Request\GetRecordById;
use MinhD\CSWClient\Request\GetRecords;
use MinhD\CSWClient\Request\Harvest;
use MinhD\CSWClient\Request\InsertRecord;
use MinhD\CSWClient\Utility\XML;

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

    public function isAccessible()
    {
        try {
            $this->webClient->get('/');
            return true;
        } catch (RequestException $e) {
            return false;
        }
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
     * @param array $options
     * @return CSWResponse
     */
    public function getRecords($options = [])
    {
        return $this->send(new GetRecords($options));
    }

    /**
     * @param array $typeNames
     * @return CSWResponse
     */
    public function describeRecord($typeNames = [])
    {
        return $this->send(new DescribeRecord($typeNames));
    }

    public function insertRecord($payload)
    {
        return $this->send(new InsertRecord($payload));
    }

    public function deleteRecord($uuid)
    {
        return $this->send(new DeleteRecord($uuid));
    }

    public function harvest($url, $type)
    {
        return $this->send(new Harvest($url, $type));
    }

    public function hasRecord($uuid)
    {
        $result = $this->send(new GetRecordById($uuid));
        $sxml = XML::getSXML($result->asXML());
        if (count($sxml->xpath('//csw:Record')) === 1) {
            return true;
        }
        return false;
    }

    /**
     * @param Request $request
     * @return CSWResponse
     * @throws CSWException
     */
    public function send(Request $request)
    {
        $result = $this->webClient->send($request);
        $cswResponse = new CSWResponse($result);
        if ($cswResponse->hasException()) {
            throw new CSWException($cswResponse);
        }
        return $cswResponse;
    }
}
