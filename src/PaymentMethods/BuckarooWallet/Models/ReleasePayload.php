<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\Payload;

class ReleasePayload extends Payload
{
    protected float $amountCredit;
}