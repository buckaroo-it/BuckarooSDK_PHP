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
        if($address->category) {
            $this->serviceList->appendParameter([
                "Name"              => "Category",
                "Value"             => $address->category,
                "GroupType"         => $groupType
            ]);
        }

        if($address->careOf) {
            $this->serviceList->appendParameter([
                "Name"              => "CareOf",
                "Value"             => $address->careOf,
                "GroupType"         => $groupType
            ]);
        }

        if($address->initials) {
            $this->serviceList->appendParameter([
                "Name"              => "Initials",
                "Value"             => $address->initials,
                "GroupType"         => $groupType
            ]);
        }

        if($address->salutation) {
            $this->serviceList->appendParameter([
                "Name"              => "Salutation",
                "Value"             => $address->salutation,
                "GroupType"         => $groupType
            ]);
        }

        $this->serviceList->appendParameter([
            "Name"              => "FirstName",
            "Value"             => $address->firstName,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "LastName",
            "Value"             => $address->lastName,
            "GroupType"         => $groupType
        ]);

        if($address->chamberOfCommerce) {
            $this->serviceList->appendParameter([
                "Name"              => "ChamberOfCommerce",
                "Value"             => $address->chamberOfCommerce,
                "GroupType"         => $groupType
            ]);
        }

        $this->serviceList->appendParameter([
            "Name"              => "Email",
            "Value"             => $address->email,
            "GroupType"         => $groupType
        ]);

        if($address->phone) {
            $this->serviceList->appendParameter([
                "Name"              => "Phone",
                "Value"             => $address->phone,
                "GroupType"         => $groupType
            ]);
        }

        if($address->mobilePhone) {
            $this->serviceList->appendParameter([
                "Name"              => "MobilePhone",
                "Value"             => $address->mobilePhone,
                "GroupType"         => $groupType
            ]);
        }

        $this->serviceList->appendParameter([
            "Name"              => "Street",
            "Value"             => $address->street,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "StreetNumber",
            "Value"             => $address->streetNumber,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "StreetNumberAdditional",
            "Value"             => $address->streetNumber,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "PostalCode",
            "Value"             => $address->postalCode,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "City",
            "Value"             => $address->city,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "Country",
            "Value"             => $address->country,
            "GroupType"         => $groupType
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "BirthDate",
            "Value"             => $address->birthDate,
            "GroupType"         => $groupType
        ]);
    }
}