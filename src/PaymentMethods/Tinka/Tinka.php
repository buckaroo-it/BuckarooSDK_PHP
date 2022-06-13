<?php

namespace Buckaroo\PaymentMethods\Tinka;

use Buckaroo\Models\Article;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Tinka\Adapters\ArticleServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\Tinka\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\TinkaCustomerParameters;

class Tinka extends PaymentMethod
{
    protected string $paymentName = 'Tinka';

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            [
                "Name"              => "PaymentMethod",
                "Value"             => $serviceParameters['paymentMethod'],
                "GroupType"         => "",
                "GroupID"           => ""
            ],
            [
                "Name"              => "DeliveryMethod",
                "Value"             => $serviceParameters['deliveryMethod'],
                "GroupType"         => "",
                "GroupID"           => ""
            ],
            [
                "Name"              => "DeliveryDate",
                "Value"             => $serviceParameters['deliveryDate'],
                "GroupType"         => "",
                "GroupID"           => ""
            ]
        ]);

        $parametersService = new ArticleParameters(new DefaultParameters($serviceList), array_map(function($article){
            return new ArticleServiceParametersKeysAdapter((new Article())->setProperties($article));
        }, $serviceParameters['articles'] ?? []));

        $parametersService = new TinkaCustomerParameters($parametersService, ['customer' => new CustomerServiceParametersKeysAdapter((new Person())->setProperties($serviceParameters['customer'] ?? []))]);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}