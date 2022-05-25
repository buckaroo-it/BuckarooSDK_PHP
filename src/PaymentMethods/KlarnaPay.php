<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

class KlarnaPay extends PaymentMethod
{
    public const SERVICE_VERSION = 0;

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            self::AFTERPAY,
            self::SERVICE_VERSION,
            'Pay'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
        $parametersService = new CustomerParameters($parametersService, $serviceParameters['customer'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        return self::KLARNA;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}