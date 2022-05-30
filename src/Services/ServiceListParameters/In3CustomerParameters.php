<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Article;
use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;

class In3CustomerParameters implements ServiceListParameter
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

        $this->appendParameter('Person', 'Culture', 'nl-NL');
        $this->appendParameter('Person', 'Gender', $customer->gender);
        $this->appendParameter('Person', 'Initials', $customer->initials);
        $this->appendParameter('Person', 'LastName', $customer->lastName);
        $this->appendParameter('Person', 'BirthDate', $customer->birthDate);

        $this->appendParameter('Email', 'Email', $customer->email);
        $this->appendParameter('Phone', 'Phone', $customer->phone);

        $this->appendParameter('Address', 'Street', $customer->address->street);
        $this->appendParameter('Address', 'HouseNumber', $customer->address->housenumber);
        $this->appendParameter('Address', 'HouseNumberSuffix', $customer->address->streetNumberAdditional);
        $this->appendParameter('Address', 'ZipCode', $customer->address->postalCode);
        $this->appendParameter('Address', 'City', $customer->address->city);
        $this->appendParameter('Address', 'Country', $customer->address->country);
    }

    private function appendParameter(string $groupType, string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              => $name,
                "Value"             => $value,
                "GroupType"         => $groupType,
                "GroupID"           => ""
            ]);
        }

        return $this;
    }
}