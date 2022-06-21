<?php

namespace Buckaroo\PaymentMethods\PayPerEmail;

use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PayPerEmail\Models\PaymentInvitation;

class PayPerEmail extends PaymentMethod
{
    protected string $paymentName = 'payperemail';

    public function paymentInvitation()
    {
        $paymentInvitation = new PaymentInvitation($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PaymentInvitation', $paymentInvitation);

        return $this->postRequest();
    }
}