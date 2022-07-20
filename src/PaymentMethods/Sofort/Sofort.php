<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Sofort;

use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;

class Sofort extends PayablePaymentMethod implements Combinable
{
    protected string $paymentName = 'sofortueberweisung';
    protected int $serviceVersion = 1;
}
