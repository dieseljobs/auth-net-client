<?php

namespace TheLHC\AuthNetClient;

class Profile
{
    private $attributes = [];

    public function __construct($attrs = [])
    {
        if (isset($attrs['payment_profiles'])) {
            foreach($attrs['payment_profiles'] as $key => $pp) {
                if (is_array($pp)) {
                    $attrs['payment_profiles'][$key] = $this->newPaymentProfile($pp);
                }
            }
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

    public function newPaymentProfile($attrs)
    {
        $payment_profile = new PaymentProfile($attrs);
        return $payment_profile;
    }
}
