<?xml version="1.0" encoding="utf-8"?>
<getCustomerPaymentProfileListRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
  @include("auth-net-client::authentication")
  <searchType>{{$request->searchType}}</searchType>
  <month>{{$request->month}}</month>
  <sorting>
  	<orderBy>{{$request->sorting["orderBy"]}}</orderBy>
    <orderDescending>{{$request->sorting["orderDescending"]}}</orderDescending>
  </sorting>
  @if($request->paging)
  <paging>
    <limit>{{$request->paging["limit"]}}</limit>
    <offset>{{$request->paging["offset"]}}</offset>
  </paging>
  @endif
</getCustomerPaymentProfileListRequest>
