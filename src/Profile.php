<?php

namespace TheLHC\AuthNetClient;

class Profile
{
    use GetsAndSetsAttributes;
    use ReturnsResponse;

    /**
     * Static finder method to retrieve Profile instance from id
     *
     * @param  string $customer_profile_id
     * @return Profile
     */
    static public function find($customer_profile_id)
    {
        $profile = new self(["customerProfileId" => $customer_profile_id]);
        $response = $profile->get();
        if (is_null($response->profile)) return null;
        $returnProfile = new self($response->profile, true);
        return $returnProfile;
    }

    /**
     * Overload constructor
     *
     * @param array  $attrs
     * @param boolean $exists
     */
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

    /**
     * Overload method
     *
     * @return string
     */
    public function __toString()
    {
        if (isset($this->attributes['paymentProfiles'])) {
            foreach($this->attributes['paymentProfiles'] as $key => $pp) {
                $this->attributes['paymentProfiles'][$key] = $pp->toArray();
            }
        }
        return json_encode($this->attributes);
    }

    /**
     * Resolve XML payload for action
     *
     * @param  string $action
     * @return string
     */
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
            case "delete":
                $template = "auth-net-client::delete-profile";
                break;
        }
        $xml = view(
            $template,
            ['profile' => $this]
        )->render();
        return $xml;
    }

    /**
     * Get child payment profiles as Collection instance
     *
     * @return Collection
     */
    public function paymentProfiles()
    {
        $collection = new Collection($this->paymentProfiles, 'paymentProfiles', $this);
        return $collection;
    }

    /**
     * Get the unique key identifier for this object
     *
     * @return string
     */
    public function getKey()
    {
        return $this->customerProfileId;
    }

    /**
     * Get the unique key identifier attribute name
     *
     * @return string
     */
    public function getKeyName()
    {
        return "customerProfileId";
    }

    /**
     * Add returned identifiers to object(s) after successful creation
     *
     * @param  Response $response
     * @return void
     */
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
