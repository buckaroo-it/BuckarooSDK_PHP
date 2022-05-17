<?php

namespace Buckaroo\Transaction;

use Buckaroo\Payload\TransactionResponse;
use Buckaroo\PaymentMethods\AuthorizePaymentInterface;

class AuthorizeTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, AuthorizePaymentInterface::class))
        {
            return $paymentMethod->authorize($this->request);
        }

        throw new \Exception("This payment method doesn't support authorize service action.");
    }
}