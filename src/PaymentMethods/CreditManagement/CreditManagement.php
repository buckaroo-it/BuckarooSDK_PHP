<?php

namespace Buckaroo\PaymentMethods\CreditManagement;

use Buckaroo\PaymentMethods\PaymentMethod;
use function Buckaroo\PaymentMethods\dd;

class CreditManagement extends PaymentMethod
{
    protected string $paymentName = 'CreditManagement3';

    public function createInvoice()
    {
        dd($this);
    }
}