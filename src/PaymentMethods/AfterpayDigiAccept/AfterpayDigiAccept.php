<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept;

use Buckaroo\Models\Article;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\AfterpayDigiAcceptCustomerParameters;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

class AfterpayDigiAccept extends PaymentMethod
{
    protected string $paymentName = 'afterpaydigiaccept';

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

        $parametersService = new ArticleParameters(new DefaultParameters($serviceList), array_map(function($article){
            return(new Article())->setProperties($article);
        }, $serviceParameters['articles'] ?? []));

        $parametersService = new AfterpayDigiAcceptCustomerParameters($parametersService,  ['customer' => (new Person())->setProperties($serviceParameters['customer'] ?? [])]);

        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}