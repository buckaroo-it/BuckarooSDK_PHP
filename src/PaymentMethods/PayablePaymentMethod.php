<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Models\Model;
use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\Models\Payload\RefundPayload;

abstract class PayablePaymentMethod extends PaymentMethod
{
    protected string $payModel = PayPayload::class;
    protected string $refundModel = RefundPayload::class;

    public function pay(?Model $model = null)
    {
        $this->setPayPayload();

        $this->setServiceList('Pay', $model);

        //TODO
        //Create validator class that validates specific request
        //$request->validate();

        return $this->postRequest();
    }

    public function payRemainder(?Model $model = null)
    {
        $this->setPayPayload();

        $this->setServiceList('PayRemainder', $model);

        return $this->postRequest();
    }

    public function refund(?Model $model = null)
    {
        $this->setRefundPayload();

        $this->setServiceList('Refund', $model);

        return $this->postRequest();
    }

    protected function setPayPayload()
    {
        $payPayload = new $this->payModel($this->payload);

        $this->request->setPayload($payPayload);

        return $this;
    }

    protected function setRefundPayload()
    {
        $refundPayload = new $this->refundModel($this->payload);

        $this->request->setPayload($refundPayload);

        return $this;
    }
}