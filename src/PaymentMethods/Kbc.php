<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;

class Kbc extends PaymentMethod
{
    public const SERVICE_VERSION = 1;

    public function paymentName(): string
    {
        return self::KBC;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
