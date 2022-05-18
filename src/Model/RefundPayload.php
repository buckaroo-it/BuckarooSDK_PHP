<?php

namespace Buckaroo\Model;

class RefundPayload extends Payload
{
    protected
        $currency,
        $amountCredit,
        $invoice,
        $pushURL,
        $originalTransactionKey;
}