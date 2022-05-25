<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;

class Sofort extends PaymentMethod
{
    public const SERVICE_VERSION = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $serviceList = new ServiceList(
            self::SOFORT,
            self::SERVICE_VERSION,
            'Pay'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList(): self
    {
        $serviceList =  new ServiceList(
            self::BANCONTACT,
            self::SERVICE_VERSION,
            'Refund'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        return self::SOFORT;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
