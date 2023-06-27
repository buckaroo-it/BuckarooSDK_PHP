<?php

namespace Buckaroo\PaymentMethods\PaymentInitiation\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $issuer;

    protected string $countryCode;
}
