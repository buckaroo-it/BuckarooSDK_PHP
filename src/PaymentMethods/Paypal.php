<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;

class Paypal extends PaymentMethod
{
    public const SERVICE_VERSION = 0;

    public function paymentName(): string
    {
        return self::PAYPAL;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}