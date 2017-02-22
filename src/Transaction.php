<?php

namespace TheLHC\AuthNetClient;

class Transaction
{

    use ReturnsResponse;

    private $attributes = [];
    private $original = [];

    public function __construct($attrs = [], $exists = false)
    {
        $this->attributes = $attrs;
        if ($exists) {
            $this->original = $attrs;
        }
    }

    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        } else {
            return null;
        }
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function toXML($action)
    {
        switch ($action) {
            case "create":
                $template = "auth-net-client::create-transaction";
                break;
        }
        $xml = view(
            $template,
            ['transaction' => $this]
        )->render();
        return $xml;
    }

    public function postCreateResponse($response)
    {
        foreach($response->transactionResponse as $key => $val) {
            $this->$key = $val;
        }
    }

}
