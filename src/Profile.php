<?php

namespace TheLHC\AuthNetClient;


class Profile
{

    use ReturnsResponse;

    private $attributes = [];
    private $isNew;

    public function __construct($attrs = [], $isNew = true)
    {
        if (isset($attrs['payment_profiles'])) {
            foreach($attrs['payment_profiles'] as $key => $pp) {
                if (is_array($pp)) {
                    $attrs['payment_profiles'][$key] = $this->newPaymentProfile($pp);
                }
            }
        }
        $this->attributes = $attrs;
        $this->isNew = $isNew;
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

    public function newPaymentProfile($attrs)
    {
        $payment_profile = new PaymentProfile($attrs);
        return $payment_profile;
    }

    public function toXML($action)
    {
        switch ($action) {
            case "create":
                $template = "auth-net-client::create-profile";
                break;
            case "get":
                $template = "auth-net-client::get-profile";
                break;
            case "update":
                $template = "auth-net-client::update-profile";
                break;
        }
        $xml = view(
            $template,
            ['profile' => $this]
        )->render();
        return $xml;
    }

    public function payment_profiles($attrs = null)
    {

    }
}
