<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Sofort extends PaymentMethod
{
    protected string $paymentName = 'sofortueberweisung';
    protected int $serviceVersion = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
