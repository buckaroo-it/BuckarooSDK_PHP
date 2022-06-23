<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Giropay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\GiftCard\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Giropay extends PayablePaymentMethod
{
    protected int $serviceVersion = 2;
    protected string $paymentName = 'giropay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }
}
