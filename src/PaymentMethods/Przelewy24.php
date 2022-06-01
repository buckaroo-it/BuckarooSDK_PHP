<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Adapters\ServiceParametersKeys\Przelewy24CustomerAdapter;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;
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

        $parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new Przelewy24CustomerAdapter((new Customer())->setProperties($serviceParameters['customer'] ?? []))]);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}