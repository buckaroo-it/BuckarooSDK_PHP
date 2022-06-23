<?php

namespace Buckaroo\PaymentMethods\Alipay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Alipay\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Alipay extends PayablePaymentMethod
{
    protected string $paymentName = 'alipay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}