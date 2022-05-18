<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Payload;
use Buckaroo\Model\ServiceList;

class CreditCard extends PaymentMethod
{
    public const SERVICE_VERSION = 2;

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

    public function getPayServiceList(Payload $payload): ServiceList
    {
        return new ServiceList(
            'CreditCard',
            self::SERVICE_VERSION,
            'Pay'
        );
    }

    public function getRefundServiceList(Payload $payload): ServiceList
    {
        return new ServiceList(
            'CreditCard',
            self::SERVICE_VERSION,
            'Refund'
        );
    }
}
