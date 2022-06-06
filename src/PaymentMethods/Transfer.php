<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\Adapters\ServiceParametersKeys\BankTransferCustomerAdapter;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\ServiceListParameters\BankTransferCustomerParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;


class Transfer extends PaymentMethod
{
    public const SERVICE_VERSION = 1;
    public const PAYMENT_NAME = 'transfer';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            [
                "Name"              => "DateDue",
                "Value"             => $serviceParameters['dateDue'],
                "GroupType"         => "",
                "GroupID"           => ""
            ],
            [
                "Name"              => "SendMail",
                "Value"             => $serviceParameters['sendMail'],
                "GroupType"         => "",
                "GroupID"           => ""
            ]
        ]);
        
        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new BankTransferCustomerParameters($parametersService, ['customer' => new BankTransferCustomerAdapter((new Customer())->setProperties($serviceParameters['customer'] ?? []))]);
        $parametersService->data();
        /*
        //$parametersService = new CustomerParameters(new DefaultParameters($serviceList), ['customer' => new BankTransferAdapter((new Customer())->setProperties($serviceParameters['customer'] ?? []))]);
        print_r($parametersService->data());
        //print_r(new Customer())->setProperties($serviceParameters['customer'] ?? []);
        //print_r(new BankTransferAdapter((new Customer())->setProperties($serviceParameters['customer'] ?? [])));
        exit;
        */
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
