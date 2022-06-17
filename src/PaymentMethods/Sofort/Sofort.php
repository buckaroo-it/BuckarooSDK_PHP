<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Sofort;

use Buckaroo\PaymentMethods\PaymentMethod;

class Sofort extends PaymentMethod
{
    protected string $paymentName = 'sofortueberweisung';
    protected int $serviceVersion = 1;
}
