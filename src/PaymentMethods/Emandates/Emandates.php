<?php

namespace Buckaroo\PaymentMethods\Emandates;

use Buckaroo\PaymentMethods\Emandates\Models\Mandate;
use Buckaroo\PaymentMethods\PaymentMethod;

class Emandates extends PaymentMethod
{
    protected string $paymentName = 'emandate';
    protected array $requiredConfigFields = ['currency'];

    public function issuerList()
    {
        $this->setServiceList('GetIssuerList');

        return $this->dataRequest();
    }

    public function createMandate()
    {
        $mandate = new Mandate($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CreateMandate', $mandate);

        return $this->dataRequest();
    }

    public function status()
    {
        $mandate = new Mandate($this->payload);

        $this->setServiceList('GetStatus', $mandate);

        return $this->dataRequest();
    }

    public function modifyMandate()
    {
        $mandate = new Mandate($this->payload);

        $this->setPayPayload();

        $this->setServiceList('ModifyMandate', $mandate);

        return $this->dataRequest();
    }

    public function cancelMandate()
    {
        $mandate = new Mandate($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CancelMandate', $mandate);

        return $this->dataRequest();
    }
}