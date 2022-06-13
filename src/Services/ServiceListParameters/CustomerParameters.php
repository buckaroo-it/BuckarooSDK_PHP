<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Address;
use Buckaroo\Models\Person;
use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;

class CustomerParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomer('Customer', $customer);

        if($customer->address) {
            $this->attachCustomerAddress('Address', $customer->address);
        }

        if($customer->billing) {
            $this->attachCustomerAddress('BillingCustomer', $customer->billing);
        }

        if($customer->shipping) {
            $this->attachCustomerAddress('ShippingCustomer', $customer->shipping);
        }

        return $this->serviceList;
    }

    protected function attachCustomer(?string $groupType, Model $customer)
    {
        $customerArray = array_diff_key($customer->toArray(), array_flip(['shipping', 'billing', 'address', 'useBillingInfoForShipping']));

        foreach($customerArray as $key => $value) {
            $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf($key), $value);
        }
    }

    protected function attachCustomerAddress(string $groupType, Model $address)
    {
        foreach($address->toArray() as $key => $value) {
            $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf($key), $value);
        }
    }
}