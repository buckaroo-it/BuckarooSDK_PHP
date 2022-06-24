<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\BankTransfer;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\BankTransfer\Models\Pay;
use Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys\PayAdapter;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class BankTransfer extends PayablePaymentMethod
{
    protected string $paymentName = 'transfer';
    protected int $serviceVersion = 1;

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new PayAdapter(new Pay($this->payload)));
    }
}
