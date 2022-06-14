<?php

namespace Buckaroo\PaymentMethods\CreditManagement;

use Buckaroo\Models\Payload;
use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\ModelParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditManagement extends PaymentMethod
{
    protected string $paymentName = 'CreditManagement3';
    protected array $requiredConfigFields = ['currency'];

    public function createInvoice()
    {
        $invoice = new Invoice($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CreateInvoice', $invoice);

        return $this->client->dataRequest(
            $this->request,
            TransactionResponse::class
        );
    }
}