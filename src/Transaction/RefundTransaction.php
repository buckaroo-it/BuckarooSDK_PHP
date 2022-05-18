<?php

namespace Buckaroo\Transaction;

use Buckaroo\Model\RefundPayload;
use Buckaroo\PaymentMethods\PaymentInterface;
use Buckaroo\Transaction\Request\Adapters\RefundPayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class RefundTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, PaymentInterface::class))
        {
            $this->setPayload(RefundPayload::class, RefundPayloadAdapter::class);

            $this->request->getServices()->pushServiceList($paymentMethod->getRefundServiceList($this->payload));

            return $paymentMethod->refund($this->request);
        }

        throw new \Exception("This payment method doesn't support refund service action.");
    }
}