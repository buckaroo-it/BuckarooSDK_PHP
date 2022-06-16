<?php

namespace Buckaroo\Models;

class Payload extends Model
{
    protected string $currency;
    protected string $returnURL;
    protected string $returnURLError;
    protected string $returnURLCancel;
    protected string $returnURLReject;
    protected string $pushURL;
    protected string $pushURLFailure;
    protected string $invoice;
    protected string $description;
    protected string $originalTransactionKey;

    public function __construct(?array $payload)
    {
        $this->invoice = uniqid($_ENV['BPE_WEBSITE'] . '_INVOICE_NO_');

        $this->setProperties($payload);

        parent::__construct();
    }
}