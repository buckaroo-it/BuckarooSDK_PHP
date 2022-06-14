<?php

namespace Buckaroo\PaymentMethods\GiftCard;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\GiftCard\Models\Pay;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class GiftCard extends PaymentMethod
{
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Pay($this->payload));
    }

    public function paymentName(): string
    {
        if(isset($this->payload['name']))
        {
            return $this->payload['name'];
        }

        throw new \Exception('Missing voucher name');
    }
}