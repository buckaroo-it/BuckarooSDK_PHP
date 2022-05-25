<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Giropay extends PaymentMethod
{
    public const SERVICE_VERSION = 2;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = array([
            'name' => 'bic',
            'Value' => $paymentModel->bic
        ]);

        $serviceList = new ServiceList(
            self::GIROPAY,
            self::SERVICE_VERSION,
            'Pay',
            $parameters
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        return self::GIROPAY;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
