<?php

namespace TheLHC\AuthNetClient;

class PaymentProfile
{
    use GetsAndSetsAttributes;
    use ReturnsResponse;

    /**
     * Static finder method, returns new instance of self from profile and
     * payment profile ids
     *
     * @param  string $customer_profile_id
     * @param  string $customer_payment_profile_id
     * @return PaymentProfile
     */
    static public function find($customer_profile_id, $customer_payment_profile_id)
    {
        $payment_profile = new self([
            "customerProfileId" => $customer_profile_id,
            "customerPaymentProfileId" => $customer_payment_profile_id,
        ]);
        $response = $payment_profile->get();
        if (is_null($response->paymentProfile)) return null;
        $returnPaymentProfile = new self($response->paymentProfile, true);
        return $returnPaymentProfile;
    }

    /**
     * Special method to retrieve a collection of PaymentProfile instances
     *
     * @param  array $params
     * @return Collection
     */
    static public function getList($params)
    {
        $instance = new self([]);
        $payload = view(
            "auth-net-client::get-payment-profile-list",
            ['request' => (object)$params]
        )->render();
        $response = $instance->postXMLPayload($payload);
        if ($response->isSuccess()) {
            $paymentProfiles = [];
            foreach($response->paymentProfiles['paymentProfile'] as $paymentProfile) {
                $paymentProfiles[] = new self($paymentProfile, true);
            }
            $collection = new Collection($paymentProfiles);
            return $collection;
        } else {
            return [];
        }
    }

    /**
     * Overload constructor
     *
     * @param array  $attrs
     * @param boolean $exists
     */
    public function __construct($attrs = [], $exists = false)
    {
        if (isset($attrs['payment']) and is_array($attrs['payment'])) {
            $attrs['payment'] = $this->newPayment($attrs['payment']);
        }
        $this->attributes = $attrs;
        if ($exists) $this->original = $attrs;
    }

    /**
     * Overload method
     *
     * @return string
     */
    public function __toString()
    {
        if (isset($this->attributes['payment'])) {
            $this->attributes['payment'] = (string)$this->attributes['payment'];
        }
        return json_encode($this->attributes);
    }

    /**
     * Overload toArray()
     *
     * @return array
     */
    public function toArray()
    {
        if (isset($this->attributes['payment'])) {
            $this->attributes['payment'] = $this->attributes['payment']->toArray();
        }
        return $this->attributes;
    }

    /**
     * Resolve payment class from input and return new instance
     *
     * @param  array $attrs
     * @return mixed
     */
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
                $template = "auth-net-client::create-payment-profile";
                break;
            case "get":
                $template = "auth-net-client::get-payment-profile";
                break;
            case "update":
                $template = "auth-net-client::update-payment-profile";
                break;
            case "delete":
                $template = "auth-net-client::delete-payment-profile";
                break;
            case "validate":
                $template = "auth-net-client::validate-payment-profile";
                break;
        }
        $xml = view(
            $template,
            ['payment_profile' => $this]
        )->render();
        return $xml;
    }

    /**
     * Add customerPaymentProfileId to object after successful creation
     *
     * @param  Response $response
     * @return void
     */
    public function postCreateResponse($response)
    {
        $this->customerPaymentProfileId = $response->customerPaymentProfileId;
    }

    /**
     * Get the unique key identifier for this object
     *
     * @return string
     */
    public function getKey()
    {
        return $this->customerPaymentProfileId;
    }

    /**
     * Get the unique key identifier attribute name
     *
     * @return string
     */
    public function getKeyName()
    {
        return "customerPaymentProfileId";
    }

    /**
     * Special method to validate payment profile on file is valid
     *
     * @return boolean
     */
    public function validate()
    {
        $payload = $this->toXML("validate");
        $response = $this->postXMLPayload($payload);
        if ($response->isSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to charge payment profile amount
     * $transaction_attrs should match possible properties in API documentation 
     *
     * @param  string $amount
     * @param  array $transaction_attrs
     * @return Transaction
     */
    public function charge($amount, $transaction_attrs = [])
    {
        $transaction_attrs = array_merge(
            [
                "transactionType" => "authCaptureTransaction",
                "amount" => $amount,
                "paymentProfile" => $this
            ],
            $transaction_attrs
        );
        $transaction = new Transaction($transaction_attrs);
        $transaction->create();
        return $transaction;
    }
}
