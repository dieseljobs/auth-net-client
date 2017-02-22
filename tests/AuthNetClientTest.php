<?php

use TheLHC\AuthNetClient\AuthNetClient;
use TheLHC\AuthNetClient\Profile;
use TheLHC\AuthNetClient\PaymentProfile;
use TheLHC\AuthNetClient\Tests\TestCase;

class AuthNetClientTest extends TestCase
{

    public function testCanResolveFromTheContainer()
    {
        $manager = $this->app->make('TheLHC\AuthNetClient\AuthNetClient');
        $this->assertInstanceOf(AuthNetClient::class, $manager);
    }

    public function testItCanCreatePaymentProfile()
    {
        $profile = new Profile([
            'merchant_customer_id' => rand(1000, 10000),
        ]);
        $paymentProfile = new PaymentProfile([
            'customerType' => 'business',
            'billTo' => [
                'firstName' => 'Aaron',
                'lastName' => 'Kaczmarek',
                'company' => 'WeaselJobs',
                'address' => '747 Main St',
                'city' => 'Westbrook',
                'state' => 'ME',
                'zip' => '04092',
                'phoneNumber' => '828-301-9460'
            ],
            'payment' => [
                'creditCard' => [
                    'number' => '4007000000027',
                    'year' => '2020',
                    'month' => '01'
                ]
            ]
        ]);
        $profile->paymentProfiles()->add($paymentProfile);
        $this->assertEquals(true, $profile->create());
        $this->assertEquals(true, !is_null($profile->customerProfileId));
        $this->assertEquals(true, !is_null($paymentProfile->customerPaymentProfileId));
    }

    public function testItCanRetrieveProfile()
    {
        $profile = Profile::find("1810689705"); //1810720109
        $this->assertEquals("TheLHC\AuthNetClient\Profile", get_class($profile));
    }

    public function testItCanUpdateProfile()
    {
        $profile = Profile::find("1810689705");
        $attrs = [
            "email" => "aaronkazman@email.com",
            "description" => "aaron test #".rand(1000, 10000)
        ];
        $this->assertEquals(true, $profile->update($attrs));
        $this->assertEquals($attrs["description"], $profile->description);
    }

    public function testItCanDeleteProfile()
    {
        $profile = Profile::find("1810720109");
        $this->assertEquals(true, $profile->delete());
    }

    public function testItCanAddPaymentProfile()
    {
        $profile = Profile::find("1810689705");
        $paymentProfile = new PaymentProfile([
            'customerType' => 'business',
            'billTo' => [
                'firstName' => 'Aaron',
                'lastName' => 'Kaczmarek',
                'company' => 'WeaselJobs #'.rand(1000, 10000),
                'address' => '747 Main St',
                'city' => 'Westbrook',
                'state' => 'ME',
                'zip' => '04092',
                'phoneNumber' => '828-301-9460'
            ],
            'payment' => [
                'creditCard' => [
                    'number' => '4012888818888',
                    'year' => '202'.rand(1, 9),
                    'month' => '0'.rand(1, 9)
                ]
            ]
        ]);
        $this->assertEquals(true, $profile->paymentProfiles()->save($paymentProfile));
        $this->assertEquals(true, !is_null($paymentProfile->customerPaymentProfileId));
    }

    /*
    public function testItCanRetrievePaymentProfile()
    {
        $authnet = $this->app->make('TheLHC\AuthNetClient\AuthNetClient');
        $profile = $authnet->profile(["id" => "1810689705"]);
        $payment_profile = $profile->paymentProfiles("1805383335");
    }
    */

}
