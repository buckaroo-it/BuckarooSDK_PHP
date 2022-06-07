<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Adapters\ServiceParametersKeys\CreditClickCustomerAdapter;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;
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

        $parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new CreditClickCustomerAdapter((new Customer())->setProperties($serviceParameters['customer'] ?? []))]);
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