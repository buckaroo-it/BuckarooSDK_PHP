<?php

namespace Buckaroo\PaymentMethods\CreditClick;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\CreditClick\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

class CreditClick extends PaymentMethod
{
    protected string $paymentName = 'creditclick';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new CustomerServiceParametersKeysAdapter((new Person())->setProperties($serviceParameters['customer'] ?? []))]);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Refund'
        );

        $serviceList->appendParameter([
            "Name"              => "refundreason",
            "Value"             => $serviceParameters['reason']
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}