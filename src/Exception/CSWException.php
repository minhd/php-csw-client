<?php


namespace MinhD\CSWClient\Exception;

use Exception;
use MinhD\CSWClient\CSWResponse;
use MinhD\CSWClient\Utility\XML;

class CSWException extends Exception
{
    protected $CSWExceptionCode = "";
    protected $CSWlocator = "";
    /**
     * CSWException constructor.
     * @param CSWResponse $response
     */
    public function __construct(CSWResponse $response)
    {
        $sxml = XML::getSXML($response->asXML());
        $code = $response->httpCode();
        $message = (string) $sxml->xpath('//ows:ExceptionText')[0];
        parent::__construct($message, $code, $previous = null);
    }
}