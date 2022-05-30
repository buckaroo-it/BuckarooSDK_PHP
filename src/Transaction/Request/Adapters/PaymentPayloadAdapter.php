<?php

namespace Buckaroo\Transaction\Request\Adapters;

use Buckaroo\Model\PaymentPayload;

class PaymentPayloadAdapter extends TransactionAdapter
{
    public function getValues(): array
    {
        return [
            'Invoice'                   => $this->payload->invoice,
            'Order'                     =>  $this->payload->order,
            'Description'               => $this->payload->description,
            'ReturnURL'                 =>  $this->payload->returnURL,
            'ReturnURLCancel'           =>  $this->payload->returnURLCancel,
            'PushURL'                   =>  $this->payload->pushURL,
            'AmountDebit'               =>  $this->payload->amountDebit,
            'Currency'                  =>  $this->payload->currency,
            'OriginalTransactionKey'    =>  $this->payload->originalTransactionKey,
        ];
    }
}