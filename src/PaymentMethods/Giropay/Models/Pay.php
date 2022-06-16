<?php

namespace Buckaroo\PaymentMethods\Giropay\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $bic;
    protected string $customerIBAN;
}