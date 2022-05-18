<?php

namespace Buckaroo\Transaction;

use Buckaroo\PaymentMethods\PaymentInterface;
use Buckaroo\Transaction\Response\TransactionResponse;

class PayTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, PaymentInterface::class))
        {
            $this->request->getServices()->pushServiceList($paymentMethod->getPayServiceList($this->payload));

            return $paymentMethod->pay($this->request);
        }

        throw new \Exception("This payment method doesn't support pay service action.");
    }
}