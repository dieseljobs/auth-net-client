<?php

use TheLHC\AuthNetClient\AuthNetClient;
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
            'merchant_customer_id' => 1,
            'payment_profiles' => [
                [
                    'customer_type' => 'business',
                    'bill_to' => [
                        'first_name' => 'Aaron',
                        'last_name' => 'Kaczmarek',
                        'company' => 'WeaselJobs',
                        'address' => '747 Main St',
                        'city' => 'Westbrook',
                        'state' => 'ME',
                        'zip' => '04092',
                        'phone_number' => '828-301-9460'
                    ],
                    'payment' => [
                        'credit_card' => [
                            'number' => '4007000000027',
                            'year' => '2020',
                            'month' => '01'
                        ]
                    ]
                ]
            ]
        ];
        //$authnet->createProfile($profile);
        $profile = $authnet->newProfile($profile);
        dd($profile);
    }

}
