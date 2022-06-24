<?php

namespace Buckaroo\PaymentMethods\Paypal;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\Paypal\Models\ExtraInfo;
use Buckaroo\PaymentMethods\Paypal\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class Paypal extends PayablePaymentMethod
{
    protected string $paymentName = 'paypal';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    public function payRecurrent(): TransactionResponse
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayRecurrent', $pay);

        return $this->postRequest();
    }

    public function extraInfo(): TransactionResponse
    {
        $extraInfo = new ExtraInfo($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Pay,ExtraInfo', $extraInfo);

        return $this->postRequest();
    }
}