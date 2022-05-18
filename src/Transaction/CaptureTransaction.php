<?php

namespace Buckaroo\Transaction;

use Buckaroo\PaymentMethods\AuthorizePaymentInterface;
use Buckaroo\Transaction\Response\TransactionResponse;

class CaptureTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, AuthorizePaymentInterface::class))
        {
            return $paymentMethod->capture($this->request);
        }

        throw new \Exception("This payment method doesn't support capture service action.");
    }
}