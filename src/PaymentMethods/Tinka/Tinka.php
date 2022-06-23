<?php

namespace Buckaroo\PaymentMethods\Tinka;

use Buckaroo\Models\Model;

use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\Tinka\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class Tinka extends PayablePaymentMethod
{
    protected string $paymentName = 'Tinka';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}