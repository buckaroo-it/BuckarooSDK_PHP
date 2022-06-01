<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;

class WeChatPay extends PaymentMethod
{
    protected string $paymentName = 'WeChatPay';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            "Name"              => "Locale",
            "Value"             => $serviceParameters['locale']
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}