<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\AfterpayDigiAcceptCustomerParameters;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

class AfterpayDigiAccept extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'afterpaydigiaccept';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            [
                "Name"      => "Accept",
                "Value"     => "TRUE" //Currently no idea what this is...
            ],
            [
                "Name"      => "B2B",
                "Value"     => ($serviceParameters['b2b'])? 'TRUE' : 'FALSE'
            ]
        ]);

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
        $parametersService = new AfterpayDigiAcceptCustomerParameters($parametersService, $serviceParameters['customer'] ?? []);
        $parametersService->data();

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