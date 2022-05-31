<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;

class WeChatPay extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'WeChatPay';

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

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}