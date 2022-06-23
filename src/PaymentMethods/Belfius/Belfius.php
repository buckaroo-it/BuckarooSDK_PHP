<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Belfius;

use Buckaroo\PaymentMethods\PayablePaymentMethod;

class Belfius extends PayablePaymentMethod
{
    protected string $paymentName = 'belfius';
}
