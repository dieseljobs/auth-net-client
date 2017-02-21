<?php

namespace TheLHC\AuthNetClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait ReturnsResponse
{
    public $errors;

    public function create()
    {
        $payload = $this->toXML("create");
        return $this->postXMLPayload($payload);
    }

    public function get()
    {
        $payload = $this->toXML("get");
        return $this->postXMLPayload($payload);
    }

    public function update($attrs = null)
    {
        if ($attrs) {
            foreach($attrs as $key => $val) {
                $this->$key = $val;
            }
        }
        $payload = $this->toXML("update");
        $response = $this->postXMLPayload($payload);
        if ($response->messages['resultCode'] == "Ok") {
            return true;
        } else {
            // set errors
            $this->errors = $response->messages['message']['text'];
            // reset attributes
            $this->attributes = $this->original;
            return false;
        }
    }

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
        $client = new Client(); //initialize GuzzleHttp
        try {
            $response = $client->post(
                config('auth_net_client.endpoint'),
                [
                  'body'    => $payload,
                  'headers' => ['Content-Type'=>'text/xml'],
                  'verify'  => false
                ]
            );
            $returnResponse = new Response($response);
            return $returnResponse;
        } catch (RequestException $e) {
            // throw the RequestException
            throw $e;
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

}
