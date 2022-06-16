<?php

namespace Buckaroo\PaymentMethods\PointOfSale;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class PointOfSale extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'pospayment';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            "Name"              => "TerminalID",
            "Value"             => $serviceParameters['terminalId']
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