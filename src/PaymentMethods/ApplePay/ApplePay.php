<?php

namespace Buckaroo\PaymentMethods\ApplePay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\ApplePay\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class ApplePay extends PaymentMethod
{
    protected string $paymentName = 'applepay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}