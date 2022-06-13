<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\BankTransfer;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\BankTransfer\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\BankTransfer\ServiceListParameters\Customer;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;


class BankTransfer extends PaymentMethod
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

        $person = new Person($serviceParameters['customer'] ?? []);

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new Customer($parametersService, ['customer' => new CustomerServiceParametersKeysAdapter($person)]);
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
