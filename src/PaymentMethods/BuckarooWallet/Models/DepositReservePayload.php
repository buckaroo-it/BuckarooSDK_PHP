<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\Payload;

class DepositReservePayload extends Payload
{
    protected string $invoice;
    protected string $originalTransactionKey;
    protected float $amountCredit;
}