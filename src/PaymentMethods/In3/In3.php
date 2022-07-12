<?php

namespace Buckaroo\PaymentMethods\In3;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\In3\Models\Pay;
use Buckaroo\PaymentMethods\In3\Models\PayPayload;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class In3 extends PayablePaymentMethod
{
    protected string $paymentName = 'Capayable';

    protected string $payModel = PayPayload::class;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    public function payInInstallments()
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayInInstallments', $pay);

        return $this->postRequest();
    }
}