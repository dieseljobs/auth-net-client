<billTo>
    @if($bill_to->first_name)
    <firstName>{{$bill_to->first_name}}</firstName>
    @endif
    @if($bill_to->last_name)
    <lastName>{{$bill_to->last_name}}</lastName>
    @endif
    @if($bill_to->company)
    <company>{{$bill_to->company}}</company>
    @endif
    @if($bill_to->address)
    <address>{{$bill_to->address}}</address>
    @endif
    @if($bill_to->city)
    <city>{{$bill_to->city}}</city>
    @endif
    @if($bill_to->state)
    <state>{{$bill_to->state}}</state>
    @endif
    @if($bill_to->zip)
    <zip>{{$bill_to->zip}}</zip>
    @endif
    @if($bill_to->phone_number)
    <phoneNumber>{{$bill_to->phone_number}}</phoneNumber>
    @endif
</billTo>
