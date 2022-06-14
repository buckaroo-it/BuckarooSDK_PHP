<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class AfterpayDigiAccept extends PaymentMethod
{
    protected string $paymentName = 'afterpaydigiaccept';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}