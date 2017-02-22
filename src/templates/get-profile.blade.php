<?xml version='1.0' encoding='utf-8'?>
<getCustomerProfileRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
    @include("auth-net-client::authentication")
    <customerProfileId>{{$profile->customerProfileId}}</customerProfileId>
</getCustomerProfileRequest>
