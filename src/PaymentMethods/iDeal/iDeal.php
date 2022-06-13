<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\iDeal;

use Buckaroo\Models\PaymentPayload;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class iDeal extends PaymentMethod
{
    protected string $paymentName = 'ideal';
    protected array $requiredConfigFields = ['currency', 'returnURL', 'returnURLCancel', 'pushURL'];

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $serviceList->appendParameter([
            "Name"              => "issuer",
            "Value"             => $serviceParameters['issuer']
        ]);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
