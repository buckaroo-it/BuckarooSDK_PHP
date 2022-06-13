<?php

namespace Buckaroo\PaymentMethods\WeChatPay;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

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