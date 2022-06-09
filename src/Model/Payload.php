<?php

namespace Buckaroo\Model;

class Payload extends Model
{
    protected string $currency;
    protected string $returnURL;
    protected string $returnURLCancel;
    protected string $pushURL;
    protected string $invoice;
    protected string $description;
    protected string $originalTransactionKey;

    public function __construct(?array $payload)
    {
        $this->invoice = uniqid($_ENV['BPE_WEBSITE'] . '_INVOICE_NO_');

        $this->setProperties($payload);
    }
}