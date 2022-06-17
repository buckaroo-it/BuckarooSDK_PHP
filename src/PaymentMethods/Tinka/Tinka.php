<?php

namespace Buckaroo\PaymentMethods\Tinka;

use Buckaroo\Models\Model;

use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Tinka\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class Tinka extends PaymentMethod
{
    protected string $paymentName = 'Tinka';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}