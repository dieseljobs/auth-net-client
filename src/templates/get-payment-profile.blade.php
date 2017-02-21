<?xml version='1.0' encoding='utf-8'?>
<getCustomerPaymentProfileRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
    @include("auth-net-client::authentication")
    <customerProfileId>{{$payment_profile->profile_id}}</customerProfileId>
    <customerPaymentProfileId>{{$payment_profile->id}}</customerPaymentProfileId>
</getCustomerPaymentProfileRequest>
