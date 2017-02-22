<?xml version='1.0' encoding='utf-8'?>
<createTransactionRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
    @include("auth-net-client::authentication")
    <transactionRequest>
        <transactionType>{{$transaction->transactionType}}</transactionType>
        <amount>{{$transaction->amount}}</amount>
        @if($transaction->paymentProfile)
        <profile>
            <customerProfileId>{{$transaction->paymentProfile->customerProfileId}}</customerProfileId>
            <paymentProfile>
                <paymentProfileId>{{$transaction->paymentProfile->customerPaymentProfileId}}</paymentProfileId>
            </paymentProfile>
        </profile>
        @endif
        @if($transaction->order)
        <order>
            @if(isset($transaction->order['invoiceNumber']))
            <invoiceNumber>{{$transaction->order['invoiceNumber']}}</invoiceNumber>
            @endif
            @if(isset($transaction->order['description']))
            <description>{{$transaction->order['description']}}</description>
            @endif
        </order>
        @endif
    </transactionRequest>
</createTransactionRequest>
