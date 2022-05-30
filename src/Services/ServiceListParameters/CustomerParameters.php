<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Address;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;

class CustomerParameters implements ServiceListParameter
{
    protected $serviceListParameter;
    protected ServiceList $serviceList;
    protected array $data;

    public function __construct(ServiceListParameter $serviceListParameter, array $data)
    {
        $this->data = $data;
        $this->serviceListParameter = $serviceListParameter;
    }

    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $this->process();

        return $this->serviceList;
    }

    private function process()
    {
        $customer = (new Customer())->setProperties($this->data);

        $this->attachCustomerAddress('BillingCustomer', $customer->billing);
        $this->attachCustomerAddress('ShippingCustomer', $customer->shipping);
    }

    private function attachCustomerAddress(string $groupType, Address $address)
    {
        $this->appendParameter($groupType, "Category", $address->category);
        $this->appendParameter($groupType, "Gender", ($address->gender)? 'Male' : 'Female');
        $this->appendParameter($groupType, "CareOf", $address->careOf);
        $this->appendParameter($groupType, "Initials", $address->initials);
        $this->appendParameter($groupType, "Salutation", $address->salutation);
        $this->appendParameter($groupType, "FirstName", $address->firstName);
        $this->appendParameter($groupType, "LastName", $address->lastName);
        $this->appendParameter($groupType, "ChamberOfCommerce", $address->chamberOfCommerce);
        $this->appendParameter($groupType, "Email", $address->email);
        $this->appendParameter($groupType, "Phone", $address->phone);
        $this->appendParameter($groupType, "MobilePhone", $address->mobilePhone);
        $this->appendParameter($groupType, "Street", $address->street);
        $this->appendParameter($groupType, "StreetNumber", $address->streetNumber);
        $this->appendParameter($groupType, "StreetNumberAdditional", $address->streetNumberAdditional);
        $this->appendParameter($groupType, "PostalCode", $address->postalCode);
        $this->appendParameter($groupType, "City", $address->city);
        $this->appendParameter($groupType, "Country", $address->country);
        $this->appendParameter($groupType, "BirthDate", $address->birthDate);
    }

    private function appendParameter(string $groupKey, string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              =>  $name,
                "Value"             => $value,
                "GroupType"         => $groupKey
            ]);
        }

        return $this;
    }
}