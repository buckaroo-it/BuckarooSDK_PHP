<?php

namespace Buckaroo\PaymentMethods\Afterpay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Afterpay\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Afterpay extends PaymentMethod
{
    protected string $paymentName = 'afterpay';
    protected int $serviceVersion = 1;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}
