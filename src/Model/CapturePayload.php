<?php

namespace Buckaroo\Model;

class CapturePayload extends Payload
{
    protected $originalTransactionKey,
        $currency,
        $amountDebit,
        $invoice;

    public function __construct(?array $payload)
    {
        $this->currency = $_ENV['BPE_EXAMPLE_CURRENCY_CODE'];

        parent::__construct($payload);
    }
}