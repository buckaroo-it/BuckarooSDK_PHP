<?php

namespace Buckaroo\PaymentMethods\Marketplaces;

use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\Marketplaces\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class Marketplaces extends PaymentMethod implements Combinable
{
    protected string $paymentName = 'Marketplaces';

    public function split()
    {
        $serviceList = new ServiceList($this->payload);

        $this->setServiceList('Split', $serviceList);

        return $this->dataRequest();
    }

    public function transfer()
    {
        $serviceList = new ServiceList($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Transfer', $serviceList);

        return $this->dataRequest();
    }

    public function refundSupplementary()
    {
        $serviceList = new ServiceList($this->payload);

        $this->setServiceList('RefundSupplementary', $serviceList);

        return $this->dataRequest();
    }
}