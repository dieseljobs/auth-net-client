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
                    'cardNumber' => '4007000000027',
                    'expirationDate' => '2020-01',
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
                    'cardNumber' => '4012888818888',
                    'expirationDate' => '202'.rand(1, 9).'0'.rand(1, 9),
                ]
            ]
        ]);
        $this->assertEquals(true, $profile->paymentProfiles()->save($paymentProfile));
        $this->assertEquals(true, !is_null($paymentProfile->customerPaymentProfileId));
    }

    public function testItCanRetrievePaymentProfile()
    {
        $payment_profile = PaymentProfile::find("1810689705", "1805383335");
        $this->assertEquals(
            "TheLHC\AuthNetClient\PaymentProfile",
            get_class($payment_profile)
        );
    }

    public function testItCanRetrievePaymentProfileFromCollection()
    {
        $profile = Profile::find("1810689705");
        $payment_profile = $profile->paymentProfiles()->find("1805383335");
        $this->assertEquals(
            "TheLHC\AuthNetClient\PaymentProfile",
            get_class($payment_profile)
        );
    }

    public function testItCanUpdatePaymentProfile()
    {
        $payment_profile = PaymentProfile::find("1810689705", "1805383335");
        $attrs = [
            'billTo' => [
                'company' => 'WeaselJobs update #'.rand(1000, 10000),
            ]
        ];
        $this->assertEquals(true, $payment_profile->update($attrs));
        $this->assertEquals(
            $attrs["billTo"]["company"],
            $payment_profile->billTo["company"]
        );
    }

    public function testItCanDeletePaymentProfile()
    {
        $payment_profile = PaymentProfile::find("1810720112", "1805415477");
        $this->assertEquals(true, $payment_profile->delete());
    }

    public function testItCanValidatePaymentProfile()
    {
        $payment_profile = PaymentProfile::find("1810689705", "1805383335");
        $this->assertEquals(true, $payment_profile->validate());
    }

    public function testItCanRetrieveProfileList()
    {
        $params = [
            "searchType" => "cardsExpiringInMonth",
            "month" => "2020-01",
            "sorting" => [
                "orderBy" => "id",
                "orderDescending" => "false",
            ],
            "paging" => [
                "limit" => 1000,
                "offset" => 1
            ]
        ];
        $payment_profiles = PaymentProfile::getList($params);
        $this->assertEquals(
            "TheLHC\AuthNetClient\Collection",
            get_class($payment_profiles)
        );
    }
}
