<?php

namespace Buckaroo\PaymentMethods\ApplePay\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $paymentData;
    protected string $customerCardName;
}