<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Adapters\ServiceParametersKeys\AfterpayDigiAcceptAddressAdapter;
use Buckaroo\Model\ServiceList;

class AfterpayDigiAcceptCustomerParameters extends CustomerParameters
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomerAddress('Billing', new AfterpayDigiAcceptAddressAdapter($customer->billing, 'Billing'));
        $this->attachCustomerAddress('Shipping', new AfterpayDigiAcceptAddressAdapter($customer->shipping, 'Shipping'));

        $this->appendParameter(null, null, "AddressesDiffer", ($customer->useBillingInfoForShipping)? 'FALSE' : 'TRUE');

        return $this->serviceList;
    }
}