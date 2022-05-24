<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;

class Eps extends PaymentMethod
{
    public const SERVICE_VERSION = 1;

    public function getCode(): string
    {
        return PaymentMethod::EPS;
    }

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $serviceList = new ServiceList(
            self::EPS,
            self::SERVICE_VERSION,
            'Pay'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList(): self
    {
        $serviceList =  new ServiceList(
            self::EPS,
            self::SERVICE_VERSION,
            'Refund'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
