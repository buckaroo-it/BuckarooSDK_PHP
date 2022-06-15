<?php

namespace Buckaroo\PaymentMethods\Alipay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Alipay\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Alipay extends PaymentMethod
{
    protected string $paymentName = 'alipay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}