<?php

namespace Buckaroo\PaymentMethods\In3;

use Buckaroo\Models\ClientIP;
use Buckaroo\Models\Company;
use Buckaroo\Models\Model;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\In3\Adapters\ArticleServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\In3\Models\Pay;
use Buckaroo\PaymentMethods\In3\Models\PayPayload;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;
use Buckaroo\Services\ServiceListParameters\CompanyParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\In3ArticleParameters;
use Buckaroo\Services\ServiceListParameters\In3CustomerParameters;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Response\TransactionResponse;

class In3 extends PaymentMethod
{
//    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'Capayable';

    protected string $payModel = PayPayload::class;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

//    public function payInInstallments()
//    {
//        $this->request->setPayload($this->getPaymentPayload());
//
//        $serviceList =  new ServiceList(
//            $this->paymentName(),
//            $this->serviceVersion(),
//            'Pay'
//        );
//
//        $serviceList->appendParameter([
//            [
//                "Name"              => "CustomerType",
//                "Value"             => $this->payload['serviceParameters']['customerType'],
//                "GroupType"         => "",
//                "GroupID"           => ""
//            ],
//            [
//                "Name"              => "InvoiceDate",
//                "Value"             => $this->payload['serviceParameters']['invoiceDate'],
//                "GroupType"         => "",
//                "GroupID"           => ""
//            ]
//        ]);
//
//        foreach($this->payload['serviceParameters']['subtotal'] as $key => $subtotal)
//        {
//            $serviceList->appendParameter([
//                [
//                    "Name"              => "Name",
//                    "Value"             => $subtotal['name'],
//                    "GroupType"         => "SubtotalLine",
//                    "GroupID"           => $key + 1
//                ],
//                [
//                    "Name"              => "Value",
//                    "Value"             => $subtotal['value'],
//                    "GroupType"         => "SubtotalLine",
//                    "GroupID"           => $key + 1
//                ]
//            ]);
//        }
//
//        $parametersService = new In3ArticleParameters(new DefaultParameters($serviceList), $this->articles($this->payload['serviceParameters']['articles'] ?? [], ArticleServiceParametersKeysAdapter::class));
//        $parametersService = new CompanyParameters($parametersService, ['company' => (new Company())->setProperties($this->payload['serviceParameters']['company'] ?? [])]);
//        $parametersService = new In3CustomerParameters($parametersService, ['customer' => (new Person())->setProperties($this->payload['serviceParameters']['customer'] ?? [])]);
//
//        $parametersService->data();
//
//        $this->request->getServices()->pushServiceList($serviceList);
//
//        return $this->postRequest();
//    }
//
//    public function setPayServiceList(array $serviceParameters = [])
//    {
//        $serviceList =  new ServiceList(
//            $this->paymentName(),
//            $this->serviceVersion(),
//            'Pay'
//        );
//
//        $serviceList->appendParameter([
//            [
//                "Name"              => "CustomerType",
//                "Value"             => $serviceParameters['customerType'],
//                "GroupType"         => "",
//                "GroupID"           => ""
//            ],
//            [
//                "Name"              => "InvoiceDate",
//                "Value"             => $serviceParameters['invoiceDate'],
//                "GroupType"         => "",
//                "GroupID"           => ""
//            ]
//        ]);
//
//        foreach($serviceParameters['subtotal'] as $key => $subtotal)
//        {
//            $serviceList->appendParameter([
//                [
//                    "Name"              => "Name",
//                    "Value"             => $subtotal['name'],
//                    "GroupType"         => "SubtotalLine",
//                    "GroupID"           => $key + 1
//                ],
//                [
//                    "Name"              => "Value",
//                    "Value"             => $subtotal['value'],
//                    "GroupType"         => "SubtotalLine",
//                    "GroupID"           => $key + 1
//                ]
//            ]);
//        }
//
//        $parametersService = new In3ArticleParameters(new DefaultParameters($serviceList), $this->articles($serviceParameters['articles'] ?? [], ArticleServiceParametersKeysAdapter::class));
//        $parametersService = new CompanyParameters($parametersService, ['company' => (new Company())->setProperties($serviceParameters['company'] ?? [])]);
//        $parametersService = new In3CustomerParameters($parametersService, ['customer' => (new Person())->setProperties($serviceParameters['customer'] ?? [])]);
//
//        $parametersService->data();
//
//        $this->request->getServices()->pushServiceList($serviceList);
//
//        return $this;
//    }
}