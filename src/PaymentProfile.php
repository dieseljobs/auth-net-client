<?php

namespace TheLHC\AuthNetClient;

class PaymentProfile
{
    private $attributes = [];

    public function __construct($attrs = [])
    {
        if (isset($attrs['payment']) and is_array($attrs['payment'])) {
            $attrs['payment'] = $this->newPayment($attrs['payment']);
        }
        $this->attributes = $attrs;
    }

    public function __get($key)
    {
        return $this->values[ $key ];
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function newPayment($attrs)
    {
        $type = array_keys($attrs)[0];
        $attrs = array_pop($attrs);
        switch ($type) {
            case "credit_card":
                return new CreditCard($attrs);
            default:
                return null;
        }
    }
}