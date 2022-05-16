<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

class CreditCard extends PaymentMethod implements PaymentInterface
{
    public function getCode(): string
    {
        return '';
    }

    public static function getCards(): array
    {
        return [
            'vpay', 'bancontactmrcash', 'cartebancaire', 'mastercard', 'visa', 'maestro', 'visaelectron',
            'cartebleuevisa', 'dankort', 'nexi', 'postepay', 'amex'
        ];
    }
}
