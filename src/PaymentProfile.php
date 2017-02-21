<?php

namespace TheLHC\AuthNetClient;

class PaymentProfile
{

    use ReturnsResponse;

    private $attributes = [];
    private $original = [];

    public function __construct($attrs = [], $exists = false)
    {
        if (isset($attrs['payment']) and is_array($attrs['payment'])) {
            $attrs['payment'] = $this->newPayment($attrs['payment']);
        }
        $this->attributes = $attrs;
        if ($exists) $this->original = $attrs;
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

    public function __toString()
    {
        if (isset($this->attributes['payment'])) {
            $this->attributes['payment'] = (string)$this->attributes['payment'];
        }
        return json_encode($this->attributes);
    }

    public function toArray()
    {
        if (isset($this->attributes['payment'])) {
            $this->attributes['payment'] = $this->attributes['payment']->toArray();
        }
        return $this->attributes;
    }

    public function newPayment($attrs)
    {
        $type = array_keys($attrs)[0];
        $attrs = array_pop($attrs);
        switch ($type) {
            case "creditCard":
                return new CreditCard($attrs);
            default:
                return null;
        }
    }

    public function toXML($action)
    {
        switch ($action) {
            case "create":
                $template = "auth-net-client::create-payment-profile";
                break;
            case "get":
                $template = "auth-net-client::get-payment-profile";
                break;
            case "update":
                $template = "auth-net-client::update-payment-profile";
                break;
        }
        $xml = view(
            $template,
            ['payment_profile' => $this]
        )->render();
        return $xml;
    }

    public function postCreateResponse($response)
    {
        // add paymentProfileId to object
        $this->customerPaymentProfileId = $response->customerPaymentProfileId;
    }
}
