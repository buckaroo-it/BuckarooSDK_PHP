<?php

namespace Buckaroo\PaymentMethods\WeChatPay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\WeChatPay\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class WeChatPay extends PayablePaymentMethod
{
    protected string $paymentName = 'WeChatPay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}