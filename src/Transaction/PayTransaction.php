<?php

namespace Buckaroo\Transaction;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\PaymentMethods\PaymentInterface;
use Buckaroo\Transaction\Request\Adapters\PaymentPayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class PayTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, PaymentInterface::class))
        {
            $this->setPayload(PaymentPayload::class, PaymentPayloadAdapter::class);

            $serviceList = $paymentMethod->getPayServiceList($this->payload, $this->payloadRequest['serviceParameters'] ?? []);

            $this->request->getServices()->pushServiceList($serviceList);

            return $paymentMethod->pay($this->request);
        }

        throw new \Exception("This payment method doesn't support pay service action.");
    }
}