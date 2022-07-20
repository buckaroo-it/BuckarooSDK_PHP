<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Giropay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\GiftCard\Models\Pay;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Giropay extends PayablePaymentMethod implements Combinable
{
    protected int $serviceVersion = 2;
    protected string $paymentName = 'giropay';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }
}
