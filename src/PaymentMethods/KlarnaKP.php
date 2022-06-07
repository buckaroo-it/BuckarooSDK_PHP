<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\KlarnaKPArticleParameters;

class KlarnaKP extends PaymentMethod
{
    protected string $paymentName = 'klarnakp';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new KlarnaKPArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}