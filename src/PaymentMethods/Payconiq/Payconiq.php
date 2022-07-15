<?php

namespace Buckaroo\PaymentMethods\Payconiq;

use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;

class Payconiq extends PayablePaymentMethod implements Combinable
{
    protected string $paymentName = 'payconiq';
}