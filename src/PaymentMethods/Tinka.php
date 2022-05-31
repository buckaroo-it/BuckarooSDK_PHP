<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Adapters\ServiceParametersKeys\TinkaArticleAdapter;
use Buckaroo\Model\Article;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CompanyParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\In3ArticleParameters;
use Buckaroo\Services\ServiceListParameters\In3CustomerParameters;

class Tinka extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'Tinka';

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
            return new TinkaArticleAdapter((new Article())->setProperties($article));
        }, $serviceParameters['articles'] ?? []));

//        $parametersService = new CompanyParameters($parametersService, $serviceParameters['company'] ?? []);
//        $parametersService = new In3CustomerParameters($parametersService, $serviceParameters['customer'] ?? []);
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