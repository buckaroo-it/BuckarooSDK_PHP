<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;

class Bancontact extends PaymentMethod
{
    public const SERVICE_VERSION = 1;
    public const PAYMENT_NAME = 'bancontactmrcash';

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
