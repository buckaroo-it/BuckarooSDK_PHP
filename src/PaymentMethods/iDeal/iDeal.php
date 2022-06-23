<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\iDeal;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\iDeal\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class iDeal extends PayablePaymentMethod
{
    protected string $paymentName = 'ideal';
    protected array $requiredConfigFields = ['currency', 'returnURL', 'returnURLCancel', 'pushURL'];

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

    public function payRemainder(?Model $model = null): TransactionResponse
    {
        return parent::payRemainder(new Pay($this->payload));
    }
}
