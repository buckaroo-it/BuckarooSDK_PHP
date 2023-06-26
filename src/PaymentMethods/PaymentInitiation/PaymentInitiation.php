<?php

namespace Buckaroo\PaymentMethods\PaymentInitiation;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\PaymentInitiation\Models\Pay;
use Buckaroo\Transaction\Response\TransactionResponse;

class PaymentInitiation extends PayablePaymentMethod
{
    protected string $paymentName = 'PayByBank';
    protected array $requiredConfigFields = ['currency', 'returnURL', 'returnURLCancel', 'pushURL'];

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }
}
