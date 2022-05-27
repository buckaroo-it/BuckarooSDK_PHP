<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Sepa extends PaymentMethod
{
    public const SERVICE_VERSION = 1;
    public const PAYMENT_NAME = 'sepadirectdebit';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = [
            ['name' => 'customeraccountname', 'Value' => $paymentModel->customerAccountName],
            ['name' => 'CustomerBIC', 'Value' => $paymentModel->customerBic],
            ['name' => 'CustomerIban', 'Value' => $paymentModel->customerIban]
        ];

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