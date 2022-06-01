<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Giropay extends PaymentMethod
{
    protected int $serviceVersion = 2;
    protected string $paymentName = 'giropay';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = array([
            'name' => 'bic',
            'Value' => $paymentModel->bic
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
