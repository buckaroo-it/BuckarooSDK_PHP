<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Sepa extends PaymentMethod
{
    protected string $paymentName = 'sepadirectdebit';
    protected int $serviceVersion = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = [
            ['name' => 'customeraccountname', 'Value' => $paymentModel->customerAccountName],
            ['name' => 'CustomerBIC', 'Value' => $paymentModel->customerBic],
            ['name' => 'CustomerIban', 'Value' => $paymentModel->customerIban]
        ];

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
