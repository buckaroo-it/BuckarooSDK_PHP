<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Sofort;

use Buckaroo\Models\PayPayload;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class Sofort extends PaymentMethod
{
    protected string $paymentName = 'sofortueberweisung';
    protected int $serviceVersion = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PayPayload($this->payload);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
