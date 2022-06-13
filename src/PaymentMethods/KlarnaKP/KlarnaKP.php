<?php

namespace Buckaroo\PaymentMethods\KlarnaKP;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
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