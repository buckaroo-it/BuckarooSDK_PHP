<?php

namespace Buckaroo\Transaction;

use Buckaroo\PaymentMethods\PaymentInterface;
use Buckaroo\Transaction\Response\TransactionResponse;

class RefundTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, PaymentInterface::class))
        {
            return $paymentMethod->refund($this->request);
        }

        throw new \Exception("This payment method doesn't support refund service action.");
    }
}