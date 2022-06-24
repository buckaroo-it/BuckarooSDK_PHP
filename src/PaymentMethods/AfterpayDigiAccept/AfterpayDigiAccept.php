<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class AfterpayDigiAccept extends PayablePaymentMethod
{
    protected string $paymentName = 'afterpaydigiaccept';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }
}