<?php

namespace Buckaroo\PaymentMethods\Przelewy24;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Przelewy24\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class Przelewy24 extends PaymentMethod
{
    protected string $paymentName = 'Przelewy24';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}