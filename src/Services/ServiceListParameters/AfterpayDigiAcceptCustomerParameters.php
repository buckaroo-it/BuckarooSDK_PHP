<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters\ArticleServiceParametersKeysAdapter;

class AfterpayDigiAcceptCustomerParameters extends CustomerParameters
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomerAddress('Billing', new ArticleServiceParametersKeysAdapter($customer->billing, 'Billing'));
        $this->attachCustomerAddress('Shipping', new ArticleServiceParametersKeysAdapter($customer->shipping, 'Shipping'));

        $this->appendParameter(null, null, "AddressesDiffer", ($customer->useBillingInfoForShipping)? 'FALSE' : 'TRUE');

        return $this->serviceList;
    }
}