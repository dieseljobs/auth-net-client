<?xml version="1.0" encoding="utf-8"?>
<deleteCustomerProfileRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
    @include("auth-net-client::authentication")
    <customerProfileId>{{$profile->customerProfileId}}</customerProfileId>
</deleteCustomerProfileRequest>
