<?php

namespace Buckaroo\PaymentMethods\iDeal\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $issuer;
}