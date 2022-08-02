<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\Payload\Payload;

class ReleasePayload extends Payload
{
    protected float $amountCredit;
}