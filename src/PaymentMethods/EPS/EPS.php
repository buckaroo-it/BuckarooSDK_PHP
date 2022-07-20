<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\EPS;

use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;

class EPS extends PayablePaymentMethod implements Combinable
{
    protected string $paymentName = 'eps';
}
