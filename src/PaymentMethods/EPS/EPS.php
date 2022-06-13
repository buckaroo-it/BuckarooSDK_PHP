<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\EPS;

use Buckaroo\PaymentMethods\PaymentMethod;

class EPS extends PaymentMethod
{
    protected string $paymentName = 'eps';
}
