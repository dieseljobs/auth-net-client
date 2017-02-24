<?php

namespace TheLHC\AuthNetClient;

class Response
{
    /**
     * Http response
     *
     * @var GuzzleHttp\Psr7\Response
     */
    private $httpResponse;

    /**
     * Response code from http response
     *
     * @var string
     */
    private $responseCode;

    /**
     * Response body from http response
     *
     * @var string
     */
    private $originalResponseBody;

    /**
     * Returned xml values as properties array
     *
     * @var array
     */
    public $body;

    /**
     * Construct new instance from http response
     *
     * @param GuzzleHttp\Psr7\Response $httpResponse
     */
    public function __construct($httpResponse)
    {
        $this->httpResponse = $httpResponse;
        $this->responseCode = $httpResponse->getStatusCode();
        $this->originalResponseBody = $httpResponse->getBody()->getContents();

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($this->originalResponseBody);

        $arr = json_decode( json_encode($xml) , 1);
        $this->body = $arr;
    }

    /**
     * Get inaccessible attribute, map to body prop
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->body[$key])) {
            return $this->body[$key];
        } else {
            return null;
        }
    }

    /**
     * Helper to determine if response is success
     *
     * @return boolean 
     */
    public function isSuccess()
    {
        return (
            $this->messages['resultCode'] == "Ok" &&
            $this->messages['message']['code'] == "I00001"
        );
    }
}
