<?php

use TheLHC\AuthNetClient\AuthNetClient;
use TheLHC\AuthNetClient\Profile;
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
        $authnet = $this->app->make('TheLHC\AuthNetClient\AuthNetClient');
        $profile = [
            'merchant_customer_id' => rand(1000, 10000),
            'paymentProfiles' => [
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
            ]
        ];
        $profile = $authnet->profile($profile);
        $response = $profile->create();
        $this->assertEquals(true, isset($response->messages["resultCode"]));
        $this->assertEquals("Ok", $response->messages["resultCode"]);
        $this->assertEquals(true, isset($response->messages["message"]));
        $this->assertEquals("Successful.", $response->messages["message"]["text"]);
        $this->assertEquals(true, !is_null($response->customerProfileId));
    }

    public function testItCanRetrieveProfile()
    {
        $authnet = $this->app->make('TheLHC\AuthNetClient\AuthNetClient');
        $profile = $authnet->profile(["id" => "1810689705"]);
        $response = $profile->get();
        $this->assertEquals(true, isset($response->messages["resultCode"]));
        $this->assertEquals("Ok", $response->messages["resultCode"]);
        $this->assertEquals(true, isset($response->messages["message"]));
        $this->assertEquals("Successful.", $response->messages["message"]["text"]);
        $this->assertEquals(true, is_array($response->profile));
    }

    public function testItCanRetrieveProfileByFindMethod()
    {
        $profile = Profile::find("1810689705");
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

    public function testItCanRetrievePaymentProfile()
    {
        $authnet = $this->app->make('TheLHC\AuthNetClient\AuthNetClient');
        $profile = $authnet->profile(["id" => "1810689705"]);
        $payment_profile = $profile->paymentProfiles("1805383335");
    }

}
