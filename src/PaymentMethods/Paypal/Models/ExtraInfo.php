<?php

namespace Buckaroo\PaymentMethods\Paypal\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys\{AddressAdapter};
use Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys\PhoneAdapter;

class ExtraInfo extends ServiceParameter
{
    protected AddressAdapter $address;
    protected PhoneAdapter $phone;
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
            return $this->address(new AddressAdapter(new Address($address)));
        }

        if($address instanceof AddressAdapter)
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
            return $this->phone(new PhoneAdapter(new Phone($phone)));
        }

        if($phone instanceof PhoneAdapter)
        {
            $this->phone = $phone;
        }

        return $this->phone;
    }
}