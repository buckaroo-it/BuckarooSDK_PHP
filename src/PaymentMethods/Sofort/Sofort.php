<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Sofort;

use Buckaroo\PaymentMethods\PayablePaymentMethod;

class Sofort extends PayablePaymentMethod
{
    protected string $paymentName = 'sofortueberweisung';
    protected int $serviceVersion = 1;
}
