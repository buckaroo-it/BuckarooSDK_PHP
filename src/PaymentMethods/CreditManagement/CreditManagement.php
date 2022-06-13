<?php

namespace Buckaroo\PaymentMethods\CreditManagement;

use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\ModelParameters;
use Buckaroo\Transaction\Response\TransactionResponse;

class CreditManagement extends PaymentMethod
{
    protected string $paymentName = 'CreditManagement3';
    protected array $requiredConfigFields = ['invoice'];

    public function createInvoice()
    {

        $parametersService = new DefaultParameters($this->request);

        $invoice = new Invoice($this->payload);

//        $serviceList = $this->getServiceList('CreateInvoice');
//
//        $parametersService = new DefaultParameters($serviceList);
//        $parametersService = new ModelParameters($parametersService, $invoice);
//        $parametersService = new ModelParameters($parametersService, $invoice->person(), 'Person');
//        $parametersService = new ModelParameters($parametersService, $invoice->address(), 'Address');
//        $parametersService = new ModelParameters($parametersService, $invoice->company(), 'Company');
//        $parametersService = new ModelParameters($parametersService, $invoice->debtor(), 'Debtor');
//        $parametersService = new ModelParameters($parametersService, $invoice->email(), 'Email');
//        $parametersService = new ModelParameters($parametersService, $invoice->phone(), 'Phone');
//
//        $parametersService->data();
//
//        $this->request->getServices()->pushServiceList($serviceList);
        dd($this->request);
        return $this->client->dataRequest(
            $this->request,
            TransactionResponse::class
        );
    }
}