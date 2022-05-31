<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

use Buckaroo\Model\Customer;
use Buckaroo\Model\Model;

class TinkaCustomerAdapter extends Model implements ServiceParameterKeysAdapter
{
    private Customer $customer;

    private array $keys = [
        'birthDate'        => 'DateOfBirth'
    ];

    public function __construct(Customer $customer) {
        $this->customer = $customer;
    }

    public function __get($property)
    {
        if (property_exists($this->customer, $property))
        {
            return $this->customer->$property;
        }

        return null;
    }

    public function serviceParameterKeyOf($propertyName): string
    {
        return (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);
    }
}