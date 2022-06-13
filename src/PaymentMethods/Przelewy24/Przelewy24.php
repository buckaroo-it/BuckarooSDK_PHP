<?php

namespace Buckaroo\PaymentMethods\Przelewy24;

use Buckaroo\Models\Adapters\ServiceParametersKeys\Przelewy24CustomerAdapter;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

class Przelewy24 extends PaymentMethod
{
    protected string $paymentName = 'Przelewy24';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new Przelewy24CustomerAdapter((new Person())->setProperties($serviceParameters['customer'] ?? []))]);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}