<?php

namespace Buckaroo\PaymentMethods\Paypal\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Paypal\Adapters\{AddressServiceParametersKeysAdapter, PhoneServiceParametersKeysAdapter};

class ExtraInfo extends ServiceParameter
{
    protected AddressServiceParametersKeysAdapter $address;
    protected PhoneServiceParametersKeysAdapter $phone;
    protected Person $customer;

    protected string $noShipping;
    protected bool $addressOverride;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['customer', 'phone', 'address']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            return $this->address(new AddressServiceParametersKeysAdapter(new Address($address)));
        }

        if($address instanceof AddressServiceParametersKeysAdapter)
        {
            $this->address = $address;
        }

        return $this->address;
    }

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            return $this->customer(new Person($customer));
        }

        if($customer instanceof Person)
        {
            $this->customer = $customer;
        }

        return $this->customer;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            return $this->phone(new PhoneServiceParametersKeysAdapter(new Phone($phone)));
        }

        if($phone instanceof PhoneServiceParametersKeysAdapter)
        {
            $this->phone = $phone;
        }

        return $this->phone;
    }
}