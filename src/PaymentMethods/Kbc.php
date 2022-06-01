<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

class Kbc extends PaymentMethod
{
    protected int $serviceVersion = 1;
    protected string $paymentName = 'kbcpaymentbutton';
}
