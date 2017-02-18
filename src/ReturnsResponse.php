<?php

namespace TheLHC\AuthNetClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait ReturnsResponse
{
    public function post()
    {
        $payload = $this->resolvePayload();
        return $this->postXMLPayload($payload);
    }

    public function resolvePayload()
    {
        return $this->toXML();
    }

    /**
     * Receive xml payload and setup http post request
     * Store all relevant data and catch response errors
     *
     * @param  string $endPoint
     * @param  string $payload
     * @return void
     */
    public function postXMLPayload($payload)
    {
        $payload = $this->minifyPayload($payload); //clean whitespace
        //$this->requestPayload = $payload; //store original payload
        $client = new Client(); //initialize GuzzleHttp
        try {
            // a successfull post will get us a response status and body
            $response = $client->post(
                $endPoint,
                [
                  'body'    => $payload,
                  'headers' => ['Content-Type'=>'text/xml'],
                  'verify'  => false
                ]
            );
            $this->responseBody = $response->getBody()->getContents();
            $this->responseStatus = $response->getStatusCode();
        } catch (RequestException $e) {
            // store the RequestException error
            $this->errors[] = $e->getMessage();
        } finally {
            if ($this->responseBody) {
                $this->loadXML();
                $this->checkForErrorsInResponse();
            }
        }
    }

    /**
     * Strip out any unneccesarry whitespace
     *
     * @param  string  $payload
     * @return void
     */
    protected function minifyPayload($payload)
    {
        return preg_replace('/(>)\s*|\s*(<)/', '$1$2', $payload);
    }

    /**
     * Load response body to XML and catch any errors in case xml is malformed
     *
     * @return void
     */
    protected function loadXML()
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($this->responseBody);
        if ($xml !== false) {
            $this->responseXML = $xml;
        } else {
            $this->errors[] = 'libxml error';
        }
    }
}
