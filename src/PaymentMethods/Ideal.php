<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Payload;
use Buckaroo\Model\ServiceList;

class Ideal extends PaymentMethod implements PaymentInterface
{
    public const BANK_CODE_ABN = 'ABNANL2A';
    public const BANK_CODE_ASN = 'ASNBNL21';
    public const BANK_CODE_BUNQ = 'BUNQNL2A';
    public const BANK_CODE_ING = 'INGBNL2A';
    public const BANK_CODE_RABO = 'RABONL2U';
    public const BANK_CODE_REGIO = 'RBRBNL21';
    public const BANK_CODE_SNS = 'SNSBNL2A';
    public const BANK_CODE_TRIODOS = 'TRIONL2U';
    public const BANK_CODE_TEST = 'BANKNL2Y';
    public const SERVICE_VERSION = 2;

    public function getCode(): string
    {
        return PaymentMethod::IDEAL;
    }

    public function getPayServiceList(Payload $payload) : ServiceList
    {
        $parameters = [
            'name' => 'issuer',
            'Value' => $payload->issuer
        ];

        return new ServiceList(
            self::IDEAL,
            self::SERVICE_VERSION,
            'Pay',
            $parameters
        );
    }

    public function getRefundServiceList(Payload $payload): ServiceList
    {
        return new ServiceList(
            self::IDEAL,
            self::SERVICE_VERSION,
            'Refund'
        );
    }
}
