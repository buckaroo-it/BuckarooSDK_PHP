<?php

namespace Buckaroo\PaymentMethods\PointOfSale;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PointOfSale\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class PointOfSale extends PaymentMethod
{
    protected string $paymentName = 'pospayment';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}