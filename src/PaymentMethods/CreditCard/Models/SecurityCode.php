<?php

namespace Buckaroo\PaymentMethods\CreditCard\Models;

use Buckaroo\Models\ServiceParameter;

class SecurityCode extends ServiceParameter
{
    protected string $encryptedSecurityCode;
}