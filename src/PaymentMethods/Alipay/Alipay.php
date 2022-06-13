<?php

namespace Buckaroo\PaymentMethods\Alipay;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class Alipay extends PaymentMethod
{
    protected string $paymentName = 'alipay';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            "Name"              => "UseMobileView",
            "Value"             => $serviceParameters['useMobileView']
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}