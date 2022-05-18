<?php

namespace Buckaroo\Transaction\Request\Adapters;

use Buckaroo\Model\PaymentPayload;

class RefundPayloadAdapter extends TransactionAdapter
{
    public function getValues(): array
    {
        return [
            'Invoice'                   => $this->payload->invoice,
            'AmountCredit'              =>  $this->payload->amountCredit,
            'OriginalTransactionKey'    => $this->payload->originalTransactionKey,
            'Currency'                  =>  $this->payload->currency,
        ];
    }
}