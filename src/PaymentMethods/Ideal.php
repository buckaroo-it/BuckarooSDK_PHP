<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Ideal extends PaymentMethod
{
    protected string $paymentName = 'ideal';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            "Name"              => "issuer",
            "Value"             => $serviceParameters['issuer']
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
