<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
use Buckaroo\Services\ServiceListParameters\CompanyParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\In3ArticleParameters;
use Buckaroo\Services\ServiceListParameters\In3CustomerParameters;

class In3 extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'Capayable';

    public function payInInstallments($payload)
    {
        $this->payload = (new PayloadService($payload))->toArray();
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

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new In3ArticleParameters($parametersService, $this->payload['serviceParameters']['articles'] ?? []);
        $parametersService = new CompanyParameters($parametersService, $this->payload['serviceParameters']['company'] ?? []);
        $parametersService = new In3CustomerParameters($parametersService, $this->payload['serviceParameters']['customer'] ?? []);
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

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new In3ArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
        $parametersService = new CompanyParameters($parametersService, $serviceParameters['company'] ?? []);
        $parametersService = new In3CustomerParameters($parametersService, $serviceParameters['customer'] ?? []);
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