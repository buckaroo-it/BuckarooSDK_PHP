<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Address;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;

class AfterpayDigiAcceptCustomerParameters implements ServiceListParameter
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

        $this->serviceList->appendParameter([
            "Name"              =>  'AddressesDiffer',
            "Value"             => ($customer->useBillingInfoForShipping)? 'FALSE' : 'TRUE'
        ]);

        $this->attachCustomerAddress('Billing', $customer->billing);
        $this->attachCustomerAddress('Shipping', $customer->shipping);
    }

    private function attachCustomerAddress(string $groupType, Address $address)
    {
        $this->appendParameter($groupType, "FirstName", $address->firstName);
        $this->appendParameter($groupType, "LastName", $address->lastName);
        $this->appendParameter($groupType, "Email", $address->email);
        $this->appendParameter($groupType, "Street", $address->street);
        $this->appendParameter($groupType, "HouseNumber", $address->housenumber);
        $this->appendParameter($groupType, "PostalCode", $address->postalCode);
        $this->appendParameter($groupType, "City", $address->city);
        $this->appendParameter($groupType, "PhoneNumber", $address->phone);
        $this->appendParameter($groupType, "BirthDate", $address->birthDate);
    }

    private function appendParameter(string $groupKey, string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              =>  $groupKey . $name,
                "Value"             => $value
            ]);
        }

        return $this;
    }
}