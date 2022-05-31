<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Address;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;

class CustomerParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $customer = (new Customer())->setProperties($this->data);

        $this->attachCustomerAddress('BillingCustomer', $customer->billing);
        $this->attachCustomerAddress('ShippingCustomer', $customer->shipping);

        return $this->serviceList;
    }

    private function attachCustomerAddress(string $groupType, Address $address)
    {
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('category'), $address->category);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('gender'), ($address->gender)? 'Male' : 'Female');
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('careOf'), $address->careOf);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('initials'), $address->initials);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('salutation'), $address->salutation);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('firstName'), $address->firstName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('lastName'), $address->lastName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('chamberOfCommerce'), $address->chamberOfCommerce);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('email'), $address->email);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('phone'), $address->phone);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('mobilePhone'), $address->mobilePhone);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('street'), $address->street);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('streetNumber'), $address->streetNumber);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('streetNumberAdditional'), $address->streetNumberAdditional);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('postalCode'), $address->postalCode);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('city'), $address->city);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('country'), $address->country);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('birthDate'), $address->birthDate);
    }
}