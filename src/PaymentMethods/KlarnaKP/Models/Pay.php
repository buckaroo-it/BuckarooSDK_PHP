<?php

namespace Buckaroo\PaymentMethods\KlarnaKP\Models;

use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected string $reservationNumber;
}