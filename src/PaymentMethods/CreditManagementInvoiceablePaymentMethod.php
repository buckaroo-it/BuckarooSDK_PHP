<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;

abstract class CreditManagementInvoiceablePaymentMethod extends PaymentMethod
{
    public function attachCreditManagementInvoice(array $payload)
    {
        $invoice = new Invoice($payload);

        $serviceList = new ServiceList('CreditManagement3',  $this->serviceVersion(), 'CreateCombinedInvoice', $invoice);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}