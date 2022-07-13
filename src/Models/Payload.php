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
    protected array $additionalParameters;
}