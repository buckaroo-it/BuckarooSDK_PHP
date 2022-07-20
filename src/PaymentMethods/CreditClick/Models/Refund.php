<?php

namespace Buckaroo\PaymentMethods\CreditClick\Models;

use Buckaroo\Models\ServiceParameter;

class Refund extends ServiceParameter
{
    protected string $refundreason;
}