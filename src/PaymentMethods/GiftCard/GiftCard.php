<?php

namespace Buckaroo\PaymentMethods\GiftCard;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\GiftCard\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class GiftCard extends PayablePaymentMethod
{
    public function pay(?Model $model = null): TransactionResponse
    {
        $this->setPayPayload();

        $pay = new Pay($this->payload);

        $this->setServiceList('Pay', $pay);

        return parent::pay($model ?? $pay);
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