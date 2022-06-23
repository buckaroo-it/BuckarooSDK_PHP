<?php

namespace Buckaroo\PaymentMethods\KlarnaPay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\KlarnaPay\Models\Pay;
use Buckaroo\PaymentMethods\KlarnaPay\Models\PayPayload;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class KlarnaPay extends PayablePaymentMethod
{
    protected string $paymentName = 'klarna';
    protected string $payModel = PayPayload::class;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}