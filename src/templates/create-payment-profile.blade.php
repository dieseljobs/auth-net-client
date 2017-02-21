<?xml version='1.0' encoding='utf-8'?>
<createCustomerPaymentProfileRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
    @include("auth-net-client::authentication")
    <customerProfileId>{{$payment_profile->customerProfileId}}</customerProfileId>
    <paymentProfile>
        @include("auth-net-client::payment-profile")
    </paymentProfile>
    <validationMode>liveMode</validationMode>
</createCustomerPaymentProfileRequest>
