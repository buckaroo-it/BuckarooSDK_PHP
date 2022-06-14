<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\SEPA;

use Buckaroo\Models\PayPayload;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class SEPA extends PaymentMethod
{
    protected string $paymentName = 'SepaDirectDebit';
    protected int $serviceVersion = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PayPayload($this->payload);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay,ExtraInfo'
        );

        $serviceList->appendParameter([
            [
                "Name"              => "CollectDate",
                "Value"             => $this->payload['serviceParameters']['collectDate'] ?? null
            ],
            [
                "Name"              => "CustomerIBAN",
                "Value"             => $this->payload['serviceParameters']['iban'] ?? null
            ],
            [
                "Name"              => "Customerbic",
                "Value"             => $this->payload['serviceParameters']['bic'] ?? null
            ],
            [
                "Name"              => "MandateReference",
                "Value"             => $this->payload['serviceParameters']['mandateReference'] ?? null
            ],
            [
                "Name"              => "MandateDate",
                "Value"             => $this->payload['serviceParameters']['mandateDate'] ?? null
            ],
            [
                "Name"              => "customeraccountname",
                "Value"             => $this->payload['serviceParameters']['customer']['name'] ?? null
            ]
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
