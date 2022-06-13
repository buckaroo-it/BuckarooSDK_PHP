<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;

class TinkaCustomerParameters extends CustomerParameters
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomer(null, $customer);

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
}