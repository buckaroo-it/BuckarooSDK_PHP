<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\BankTransfer;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\BankTransfer\Adapters\PayServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\BankTransfer\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class BankTransfer extends PaymentMethod
{
    protected string $paymentName = 'transfer';
    protected int $serviceVersion = 1;

    public function pay(?Model $model = null): TransactionResponse
    {
        $pay = new PayServiceParametersKeysAdapter(new Pay($this->payload));

        return parent::pay($pay);
    }
}
