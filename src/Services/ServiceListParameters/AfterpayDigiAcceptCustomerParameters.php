<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Address;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;

class AfterpayDigiAcceptCustomerParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $customer = (new Customer())->setProperties($this->data);

        $this->attachCustomerAddress('Billing', $customer->billing);
        $this->attachCustomerAddress('Shipping', $customer->shipping);

        $this->appendParameter(
            null,
            null,
            "AddressesDiffer",
            ($customer->useBillingInfoForShipping)? 'FALSE' : 'TRUE'
        );

        return $this->serviceList;
    }

    private function attachCustomerAddress(string $groupType, Address $address)
    {
        $this->appendParameter(null, $groupType, "FirstName", $address->firstName);
        $this->appendParameter(null, $groupType, "LastName", $address->lastName);
        $this->appendParameter(null, $groupType, "Email", $address->email);
        $this->appendParameter(null, $groupType, "Street", $address->street);
        $this->appendParameter(null, $groupType, "HouseNumber", $address->housenumber);
        $this->appendParameter(null, $groupType, "PostalCode", $address->postalCode);
        $this->appendParameter(null, $groupType, "City", $address->city);
        $this->appendParameter(null, $groupType, "PhoneNumber", $address->phone);
        $this->appendParameter(null, $groupType, "BirthDate", $address->birthDate);
    }
}