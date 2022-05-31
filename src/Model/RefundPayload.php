<?php

namespace Buckaroo\Model;

class RefundPayload extends Payload
{
    protected
        $currency,
        $amountCredit,
        $invoice,
        $pushURL,
        $description,
        $originalTransactionKey;

    public function __construct(?array $payload)
    {
        $this->currency = $_ENV['BPE_EXAMPLE_CURRENCY_CODE'];

        parent::__construct($payload);
    }
}