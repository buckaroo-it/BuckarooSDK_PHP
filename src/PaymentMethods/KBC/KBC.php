<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\KBC;

use Buckaroo\PaymentMethods\PaymentMethod;

class KBC extends PaymentMethod
{
    protected int $serviceVersion = 1;
    protected string $paymentName = 'kbcpaymentbutton';
}
