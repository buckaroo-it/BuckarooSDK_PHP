<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Belfius;

use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;

class Belfius extends PayablePaymentMethod implements Combinable
{
    protected string $paymentName = 'belfius';
}
