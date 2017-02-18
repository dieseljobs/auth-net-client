<customerType>{{$payment_profile->customer_type}}</customerType>
@if($payment_profile->bill_to)
@include("auth-net-client::bill-to", ["bill_to" => (object)$payment_profile->bill_to])
@endif
<payment>
    @if(get_class($payment_profile->payment) == "TheLHC\AuthNetClient\CreditCard")
    @include("auth-net-client::credit-card", ["credit_card" => $payment_profile->payment])
    @endif
</payment>
