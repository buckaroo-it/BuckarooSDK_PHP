<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\KBC;

use Buckaroo\PaymentMethods\PayablePaymentMethod;

class KBC extends PayablePaymentMethod
{
    protected int $serviceVersion = 1;
    protected string $paymentName = 'kbcpaymentbutton';
}
