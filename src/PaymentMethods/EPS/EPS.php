<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\EPS;

use Buckaroo\PaymentMethods\PayablePaymentMethod;

class EPS extends PayablePaymentMethod
{
    protected string $paymentName = 'eps';
}
