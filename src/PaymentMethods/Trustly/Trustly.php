<?php

namespace Buckaroo\PaymentMethods\Trustly;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Trustly\Adapters\PayServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\Trustly\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class Trustly extends PaymentMethod
{
    protected string $paymentName = 'Trustly';

    public function pay(?Model $model = null): TransactionResponse
    {
        $pay = new PayServiceParametersKeysAdapter(new Pay($this->payload));

        return parent::pay($pay);
    }
}