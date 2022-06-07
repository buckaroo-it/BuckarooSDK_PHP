<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Adapters\ServiceParametersKeys\In3ArticleAdapter;
use Buckaroo\Model\ClientIP;
use Buckaroo\Model\Company;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;
use Buckaroo\PaymentMethods\Traits\HasArticleAndCustomerParameters;
use Buckaroo\Services\ServiceListParameters\CompanyParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\In3ArticleParameters;
use Buckaroo\Services\ServiceListParameters\In3CustomerParameters;
use Buckaroo\Transaction\Client;

class In3 extends PaymentMethod
{
    use HasArticleAndCustomerParameters;

    protected string $paymentName = 'Capayable';

    public function __construct(Client $client, ?string $serviceCode)
    {
        parent::__construct($client, $serviceCode);

        $this->request->setPayload([
            'ClientIP'      => new ClientIP
        ]);
    }

    public function payInInstallments()
    {
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            [
                "Name"              => "CustomerType",
                "Value"             => $this->payload['serviceParameters']['customerType'],
                "GroupType"         => "",
                "GroupID"           => ""
            ],
            [
                "Name"              => "InvoiceDate",
                "Value"             => $this->payload['serviceParameters']['invoiceDate'],
                "GroupType"         => "",
                "GroupID"           => ""
            ]
        ]);

        foreach($this->payload['serviceParameters']['subtotal'] as $key => $subtotal)
        {
            $serviceList->appendParameter([
                [
                    "Name"              => "Name",
                    "Value"             => $subtotal['name'],
                    "GroupType"         => "SubtotalLine",
                    "GroupID"           => $key + 1
                ],
                [
                    "Name"              => "Value",
                    "Value"             => $subtotal['value'],
                    "GroupType"         => "SubtotalLine",
                    "GroupID"           => $key + 1
                ]
            ]);
        }

        $parametersService = new In3ArticleParameters(new DefaultParameters($serviceList), $this->articles($this->payload['serviceParameters']['articles'] ?? [], In3ArticleAdapter::class));
        $parametersService = new CompanyParameters($parametersService, ['company' => (new Company())->setProperties($this->payload['serviceParameters']['company'] ?? [])]);
        $parametersService = new In3CustomerParameters($parametersService, ['customer' => (new Customer())->setProperties($this->payload['serviceParameters']['customer'] ?? [])]);

        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            [
                "Name"              => "CustomerType",
                "Value"             => $serviceParameters['customerType'],
                "GroupType"         => "",
                "GroupID"           => ""
            ],
            [
                "Name"              => "InvoiceDate",
                "Value"             => $serviceParameters['invoiceDate'],
                "GroupType"         => "",
                "GroupID"           => ""
            ]
        ]);

        foreach($serviceParameters['subtotal'] as $key => $subtotal)
        {
            $serviceList->appendParameter([
                [
                    "Name"              => "Name",
                    "Value"             => $subtotal['name'],
                    "GroupType"         => "SubtotalLine",
                    "GroupID"           => $key + 1
                ],
                [
                    "Name"              => "Value",
                    "Value"             => $subtotal['value'],
                    "GroupType"         => "SubtotalLine",
                    "GroupID"           => $key + 1
                ]
            ]);
        }

        $parametersService = new In3ArticleParameters(new DefaultParameters($serviceList), $this->articles($serviceParameters['articles'] ?? [], In3ArticleAdapter::class));
        $parametersService = new CompanyParameters($parametersService, ['company' => (new Company())->setProperties($serviceParameters['company'] ?? [])]);
        $parametersService = new In3CustomerParameters($parametersService, ['customer' => (new Customer())->setProperties($serviceParameters['customer'] ?? [])]);

        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}