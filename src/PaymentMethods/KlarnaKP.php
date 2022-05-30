<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\KlarnaKPArticleParameters;

class KlarnaKP extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'klarnakp';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Pay'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new KlarnaKPArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
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