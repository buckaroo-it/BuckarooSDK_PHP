<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;

class In3CustomerParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->appendParameter(null,'Person', 'Culture', 'nl-NL'); //Currently this is the only option
        $this->appendParameter(null,'Person', 'Gender', $customer->gender);
        $this->appendParameter(null,'Person', 'Initials', $customer->initials);
        $this->appendParameter(null,'Person', 'LastName', $customer->lastName);
        $this->appendParameter(null,'Person', 'BirthDate', $customer->birthDate);

        $this->appendParameter(null,'Email', 'Email', $customer->email);
        $this->appendParameter(null,'Phone', 'Phone', $customer->phone);

        $this->appendParameter(null,'Address', 'Street', $customer->address->street);
        $this->appendParameter(null,'Address', 'HouseNumber', $customer->address->housenumber);
        $this->appendParameter(null,'Address', 'HouseNumberSuffix', $customer->address->streetNumberAdditional);
        $this->appendParameter(null,'Address', 'ZipCode', $customer->address->postalCode);
        $this->appendParameter(null,'Address', 'City', $customer->address->city);
        $this->appendParameter(null,'Address', 'Country', $customer->address->country);

        return $this->serviceList;
    }
}