<?php

namespace Buckaroo\Transaction;

use Buckaroo\Payload\TransactionResponse;
use Buckaroo\PaymentMethods\PaymentInterface;

class CaptureTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, PaymentInterface::class))
        {
            return $paymentMethod->capture($this->request);
        }

        throw new \Exception("This payment method doesn't support capture service action.");
    }
}