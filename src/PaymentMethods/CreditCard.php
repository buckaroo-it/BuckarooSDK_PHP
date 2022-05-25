<?php
namespace Buckaroo\PaymentMethods;

class CreditCard extends PaymentMethod
{
    public const SERVICE_VERSION = 2;

    public static function getCards(): array
    {
        return [
            'vpay', 'bancontactmrcash', 'cartebancaire', 'mastercard', 'visa', 'maestro', 'visaelectron',
            'cartebleuevisa', 'dankort', 'nexi', 'postepay', 'amex'
        ];
    }

    public function paymentName(): string
    {
        return '';
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
