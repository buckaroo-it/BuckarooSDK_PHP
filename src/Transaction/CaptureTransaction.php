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
            $this->setPayload(RefundPayload::class, RefundPayloadAdapter::class);

            $serviceList = $paymentMethod->getPayServiceList($this->payload, $this->payloadRequest['serviceParameters'] ?? []);

            $this->request->getServices()->pushServiceList($serviceList);

            return $paymentMethod->capture($this->request);
        }

        throw new \Exception("This payment method doesn't support capture service action.");
    }
}