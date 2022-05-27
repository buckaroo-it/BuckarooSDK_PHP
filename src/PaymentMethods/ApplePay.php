<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class ApplePay extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'applepay';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = array([
            'name' => 'PaymentData',
            'Value' => $paymentModel->paymentData
        ],
        [
            'name' => 'CustomerCardName',
            'Value' => $paymentModel->customerCardName
        ]);

        $serviceList = new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Pay',
            $parameters
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}