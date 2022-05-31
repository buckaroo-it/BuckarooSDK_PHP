<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Customer;
use Buckaroo\Model\Model;
use Buckaroo\Model\ServiceList;

class AfterpayDigiAcceptCustomerParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $customer = (new Customer())->setProperties($this->data);

        $this->attachCustomerAddress('Billing', $customer->billing);
        $this->attachCustomerAddress('Shipping', $customer->shipping);

        $this->appendParameter(null, null, "AddressesDiffer", ($customer->useBillingInfoForShipping)? 'FALSE' : 'TRUE');

        return $this->serviceList;
    }

    private function attachCustomerAddress(string $groupType, Model $address)
    {
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('firstName'), $address->firstName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('lastName'), $address->lastName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('email'), $address->email);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('street'), $address->street);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('housenumber'), $address->housenumber);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('postalCode'), $address->postalCode);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('city'), $address->city);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('phone'), $address->phone);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('birthDate'), $address->birthDate);
    }
}