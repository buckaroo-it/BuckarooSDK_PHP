<?php

namespace Buckaroo\PaymentMethods\GooglePay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\GooglePay\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class GooglePay extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'googlepay';

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function payRemainder(?Model $model = null): TransactionResponse
    {
        return parent::payRemainder($model ?? new Pay($this->payload));
    }
}
