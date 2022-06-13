<?php

namespace Buckaroo\PaymentMethods\ApplePay;

use Buckaroo\Models\PaymentPayload;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class ApplePay extends PaymentMethod
{
    protected string $paymentName = 'applepay';

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
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay',
            $parameters
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}