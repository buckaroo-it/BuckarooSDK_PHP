<?php

namespace Buckaroo\PaymentMethods\Surepay;

use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Surepay\Models\Verify;

class Surepay extends PaymentMethod
{
    protected string $paymentName = 'surepay';

    public function verify()
    {
        $verify = new Verify($this->payload);

        $this->setServiceList('verify', $verify);

        return $this->dataRequest();
    }

}