<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Ideal extends PaymentMethod
{
    protected string $paymentName = 'ideal';
    protected int $serviceVersion = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = array([
            'name' => 'issuer',
            'Value' => $paymentModel->issuer
        ]);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay',
            $parameters
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
