<?php

namespace Buckaroo\PaymentMethods\CreditClick;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\CreditClick\Models\Pay;
use Buckaroo\PaymentMethods\CreditClick\Models\Refund;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditClick extends PayablePaymentMethod
{
    protected string $paymentName = 'creditclick';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

    public function refund(?Model $model = null): TransactionResponse
    {
        return parent::refund(new Refund($this->payload));
    }
}