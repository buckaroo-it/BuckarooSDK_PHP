<?php

namespace Buckaroo\PaymentMethods\PointOfSale;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\PointOfSale\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class PointOfSale extends PayablePaymentMethod
{
    protected string $paymentName = 'pospayment';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}