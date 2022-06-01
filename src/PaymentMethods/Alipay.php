<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;

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