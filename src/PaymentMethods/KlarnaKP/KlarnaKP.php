<?php

namespace Buckaroo\PaymentMethods\KlarnaKP;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\KlarnaKP\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class KlarnaKP extends PaymentMethod
{
    protected string $paymentName = 'klarnakp';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}