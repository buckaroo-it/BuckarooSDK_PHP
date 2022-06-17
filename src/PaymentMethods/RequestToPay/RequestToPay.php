<?php

namespace Buckaroo\PaymentMethods\RequestToPay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\RequestToPay\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class RequestToPay extends PaymentMethod
{
    protected string $paymentName = 'RequestToPay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}