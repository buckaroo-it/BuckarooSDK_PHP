<?php

namespace Buckaroo\Transaction\Request\Adapters;

class CapturePayloadAdapter extends TransactionAdapter
{
    public function getValues()
    {
        return [
            'OriginalTransactionKey'    => $this->payload->originalTransactionKey,
            'Currency'                  =>  $this->payload->currency,
            'AmountDebit'               =>  $this->payload->amountDebit,
            'Invoice'                   =>  $this->payload->invoice
        ];
    }
}