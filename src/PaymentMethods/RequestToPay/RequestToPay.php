<?php

namespace Buckaroo\PaymentMethods\RequestToPay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\RequestToPay\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class RequestToPay extends PayablePaymentMethod
{
    protected string $paymentName = 'RequestToPay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }
}