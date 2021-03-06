<customerType>{{$payment_profile->customerType}}</customerType>
@if($payment_profile->billTo)
@include("auth-net-client::bill-to", ["billTo" => (object)$payment_profile->billTo])
@endif
@if($payment_profile->payment)
<payment>
    @if(get_class($payment_profile->payment) == "TheLHC\AuthNetClient\CreditCard")
    @include("auth-net-client::credit-card", ["creditCard" => $payment_profile->payment])
    @endif
</payment>
@endif
@if($payment_profile->customerPaymentProfileId)
<customerPaymentProfileId>{{$payment_profile->customerPaymentProfileId}}</customerPaymentProfileId>
@endif
