<?php

namespace TheLHC\AuthNetClient;

class Response
{
    private $httpResponse;

    private $responseCode;

    private $originalResponseBody;

    public $body;

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

    public function __get($key)
    {
        if (isset($this->body[$key])) {
            return $this->body[$key];
        } else {
            return null;
        }
    }

    public function isSuccess()
    {
        return (
            $this->messages['resultCode'] == "Ok" &&
            $this->messages['message']['code'] == "I00001"
        );
    }
}
