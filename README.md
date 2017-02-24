# auth-net-client
An Authorize.net API client

## Installation ##

    composer require thelhc/auth-net-client

## Basic Usage ##

### New Profile

    $profile = new Profile([
        'merchant_customer_id' => rand(1000, 10000),
    ]);
    $profile->create()

### New Profile with Payment Profile

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
    $profile->create()

### Get Profile

    $profile = Profile::find("1810689705");

### Update Profile

    $profile = Profile::find("1810689705");
    $attrs = [
        "email" => "aaronkazman@email.com",
        "description" => "aaron test #".rand(1000, 10000)
    ];
    $profile->update($attrs);

### Delete Profile

    $profile = Profile::find("1810720109");
    $profile->delete();

### Create Payment Profile

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
    $profile->paymentProfiles()->save($paymentProfile);

### Get Payment Profile

    $payment_profile = PaymentProfile::find("1810689705", "1805383335");

or from collection

    $profile = Profile::find("1810689705");
    $payment_profile = $profile->paymentProfiles()->find("1805383335");

### Update Payment Profile

    $payment_profile = PaymentProfile::find("1810689705", "1805383335");
    $attrs = [
        'billTo' => [
            'company' => 'WeaselJobs update #'.rand(1000, 10000),
        ]
    ];
    $payment_profile->update($attrs);

### Delete Payment Profile

    $payment_profile = PaymentProfile::find("1810720112", "1805415477");
    $payment_profile->delete();

### Validate Payment Profile

    $payment_profile = PaymentProfile::find("1810689705", "1805383335");
    $payment_profile->validate();

### Get Payment Profile List

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

### Charge Payment payment_profiles

    $payment_profile = PaymentProfile::find("1810689705", "1805383335");
    $transaction = $payment_profile->charge("100.00", [
        "order" => [
            "invoiceNumber" => rand(1000, 10000),
            "description" => "Test payment profile charge"
        ]
    ]);
