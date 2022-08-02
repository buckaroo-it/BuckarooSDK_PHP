<?php

namespace Buckaroo\PaymentMethods\Emandates;

use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\PaymentMethods\Emandates\Models\Mandate;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PaymentMethod;

class Emandates extends PaymentMethod implements Combinable
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

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

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

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('ModifyMandate', $mandate);

        return $this->dataRequest();
    }

    public function cancelMandate()
    {
        $mandate = new Mandate($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('CancelMandate', $mandate);

        return $this->dataRequest();
    }
}