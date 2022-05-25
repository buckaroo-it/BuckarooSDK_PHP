<?php

namespace Buckaroo\PaymentMethods;

class Payconiq extends PaymentMethod
{
    public const SERVICE_VERSION = 0;

    public function paymentName(): string
    {
        return self::PAYCONIQ;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}