<?php

namespace Buckaroo\PaymentMethods\ApplePay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\ApplePay\Models\Pay;
use Buckaroo\PaymentMethods\ApplePay\Models\PayPayload;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class ApplePay extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'applepay';

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    public function payRedirect(): TransactionResponse
    {
        $this->payModel = PayPayload::class;

        $pay = new PayPayload($this->payload);

        $this->setPayPayload();
        
        return $this->postRequest();
    }
}
