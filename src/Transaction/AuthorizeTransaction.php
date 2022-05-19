<?php

namespace Buckaroo\Transaction;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\PaymentMethods\AuthorizePaymentInterface;
use Buckaroo\Transaction\Request\Adapters\PaymentPayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class AuthorizeTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, AuthorizePaymentInterface::class))
        {
            $this->setPayload(PaymentPayload::class, PaymentPayloadAdapter::class);

            $serviceList = $paymentMethod->getAuthorizeServiceList($this->payload, $this->payloadRequest['serviceParameters'] ?? []);

            $this->request->getServices()->pushServiceList($serviceList);

            return $paymentMethod->authorize($this->request);
        }

        throw new \Exception("This payment method doesn't support authorize service action.");
    }
}