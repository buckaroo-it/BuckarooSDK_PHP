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
        $this->appendParameter(null, $groupType, "Category", $address->category);
        $this->appendParameter(null, $groupType, "Gender", ($address->gender)? 'Male' : 'Female');
        $this->appendParameter(null, $groupType, "CareOf", $address->careOf);
        $this->appendParameter(null, $groupType, "Initials", $address->initials);
        $this->appendParameter(null, $groupType, "Salutation", $address->salutation);
        $this->appendParameter(null, $groupType, "FirstName", $address->firstName);
        $this->appendParameter(null, $groupType, "LastName", $address->lastName);
        $this->appendParameter(null, $groupType, "ChamberOfCommerce", $address->chamberOfCommerce);
        $this->appendParameter(null, $groupType, "Email", $address->email);
        $this->appendParameter(null, $groupType, "Phone", $address->phone);
        $this->appendParameter(null, $groupType, "MobilePhone", $address->mobilePhone);
        $this->appendParameter(null, $groupType, "Street", $address->street);
        $this->appendParameter(null, $groupType, "StreetNumber", $address->streetNumber);
        $this->appendParameter(null, $groupType, "StreetNumberAdditional", $address->streetNumberAdditional);
        $this->appendParameter(null, $groupType, "PostalCode", $address->postalCode);
        $this->appendParameter(null, $groupType, "City", $address->city);
        $this->appendParameter(null, $groupType, "Country", $address->country);
        $this->appendParameter(null, $groupType, "BirthDate", $address->birthDate);
    }
}