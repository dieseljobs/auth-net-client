<?xml version='1.0' encoding='utf-8'?>
<createCustomerProfileRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
    @include("auth-net-client::authentication")
    <profile>
        <merchantCustomerId>{{$profile->merchant_customer_id}}</merchantCustomerId>
        @if($profile->description)
        <description>{{$profile->description}}</description>
        @endif
        @if($profile->email)
        <email>{{$profile->email}}</email>
        @endif
        @if($profile->payment_profiles)
        <paymentProfiles>
            @foreach($profile->payment_profiles as $payment_profile)
            @include("auth-net-client::payment-profile", compact("payment_profile"))
            @endforeach
        </paymentProfiles>
        @endif
    </profile>
    <validationMode>liveMode</validationMode>
</createCustomerProfileRequest>
