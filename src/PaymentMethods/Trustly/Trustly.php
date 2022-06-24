<?php

namespace Buckaroo\PaymentMethods\Trustly;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\Trustly\Models\Pay;
use Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys\PayAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class Trustly extends PayablePaymentMethod
{
    protected string $paymentName = 'Trustly';

    public function pay(?Model $model = null): TransactionResponse
    {
        $pay = new PayAdapter(new Pay($this->payload));

        return parent::pay($pay);
    }
}