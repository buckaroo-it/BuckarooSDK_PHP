<?php

namespace Buckaroo\PaymentMethods\KlarnaKP;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\KlarnaKP\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class KlarnaKP extends PayablePaymentMethod
{
    protected string $paymentName = 'klarnakp';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}