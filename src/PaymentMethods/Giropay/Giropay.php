<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Giropay;

use Buckaroo\Models\PayPayload;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class Giropay extends PaymentMethod
{
    protected int $serviceVersion = 2;
    protected string $paymentName = 'giropay';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PayPayload($this->payload);

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
