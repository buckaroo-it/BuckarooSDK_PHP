<?php

namespace Buckaroo\PaymentMethods\PointOfSale\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $terminalID;
}