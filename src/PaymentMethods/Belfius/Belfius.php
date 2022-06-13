<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Belfius;

use Buckaroo\PaymentMethods\PaymentMethod;

class Belfius extends PaymentMethod
{
    protected string $paymentName = 'belfius';
}
