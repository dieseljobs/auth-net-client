<?xml version='1.0' encoding='utf-8'?>
<updateCustomerProfileRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
    @include("auth-net-client::authentication")
    <profile>
        @if($profile->merchant_customer_id)
        <merchantCustomerId>{{$profile->merchant_customer_id}}</merchantCustomerId>
        @endif
        @if($profile->description)
        <description>{{$profile->description}}</description>
        @endif
        @if($profile->email)
        <email>{{$profile->email}}</email>
        @endif
        <customerProfileId>{{$profile->id}}</customerProfileId>
    </profile>
</updateCustomerProfileRequest>
