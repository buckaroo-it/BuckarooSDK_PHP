<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Address;
use Buckaroo\Model\Customer;
use Buckaroo\Model\Model;
use Buckaroo\Model\ServiceList;

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
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('gender'), $customer->gender);
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('initials'), $customer->initials);
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('firstName'), $customer->firstName);
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('lastName'), $customer->lastName);
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('birthDate'), $customer->birthDate);
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('email'), $customer->email);
        $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf('phone'), $customer->phone);
    }

    protected function attachCustomerAddress(string $groupType, Address $address)
    {
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('category'), $address->category);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('gender'), $address->gender);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('careOf'), $address->careOf);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('initials'), $address->initials);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('salutation'), $address->salutation);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('firstName'), $address->firstName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('prefixLastName'), $address->prefixLastName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('lastName'), $address->lastName);
        $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf('externalName'), $address->externalName);
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