<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;

class Paypal extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'paypal';

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}