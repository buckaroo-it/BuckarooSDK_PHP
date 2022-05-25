<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;

class Paypal extends PaymentMethod
{
    public const SERVICE_VERSION = 0;

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            self::PAYPAL,
            self::SERVICE_VERSION,
            'Pay'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList()
    {
        $serviceList =  new ServiceList(
            self::PAYPAL,
            self::SERVICE_VERSION,
            'Refund'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}