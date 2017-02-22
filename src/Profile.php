<?php

namespace TheLHC\AuthNetClient;


class Profile
{

    use ReturnsResponse;

    private $attributes = [];
    private $original = [];

    static public function find($customer_profile_id)
    {
        $profile = new self(["id" => $customer_profile_id]);
        $response = $profile->get();
        if (is_null($response->profile)) return null;
        $returnProfile = new self($response->profile, true);
        return $returnProfile;
    }

    public function __construct($attrs = [], $exists = false)
    {
        if (isset($attrs['paymentProfiles'])) {
            $pps = $attrs['paymentProfiles'];
            unset($attrs['paymentProfiles']);
            if (isset($pps[0])) {
                // array of paymentProfiles
                foreach($pps as $key => $pp) {
                    if (is_array($pp)) {
                        $attrs['paymentProfiles'][] = new PaymentProfile($pps[$key]);
                    }
                }
            } else {
                // single paymentProfile
                $attrs['paymentProfiles'][] = new PaymentProfile($pps);
            }
        }
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

    public function __toString()
    {
        if (isset($this->attributes['paymentProfiles'])) {
            foreach($this->attributes['paymentProfiles'] as $key => $pp) {
                $this->attributes['paymentProfiles'][$key] = $pp->toArray();
            }
        }
        return json_encode($this->attributes);
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

    public function paymentProfiles()
    {
        $collection = new Collection($this->paymentProfiles, 'paymentProfiles', $this);
        return $collection;
    }

    public function getKey()
    {
        return "customerProfileId";
    }

    public function pushToArrAttr($key, $val)
    {
        $this->attributes[$key][] = $val;
    }

    public function postCreateResponse($response)
    {
        $this->customerProfileId = $response->customerProfileId;
        if ($response->customerPaymentProfileIdList) {
            $index = 0;
            foreach($response->customerPaymentProfileIdList as $id) {
                $this->attributes["paymentProfiles"][$index]->customerPaymentProfileId = $id;
                $index++;
            }
        }
    }

}
