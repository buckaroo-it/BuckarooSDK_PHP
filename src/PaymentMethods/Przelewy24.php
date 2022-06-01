<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Adapters\ServiceParametersKeys\Przelewy24CustomerAdapter;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

class Przelewy24 extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'Przelewy24';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Pay'
        );

        $parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new Przelewy24CustomerAdapter((new Customer())->setProperties($serviceParameters['customer'] ?? []))]);
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