<?php
namespace Buckaroo\PaymentMethods;

class CreditCard extends PaymentMethod
{
    public const SERVICE_VERSION = 2;
    public const PAYMENT_NAME = 'creditcard';

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
