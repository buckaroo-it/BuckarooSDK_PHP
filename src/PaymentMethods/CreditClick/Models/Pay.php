<?php

namespace Buckaroo\PaymentMethods\CreditClick\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected Person $customer;
    protected Email $email;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['customer', 'email']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
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

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new Email($email));
        }

        if($email instanceof Email)
        {
            $this->email = $email;
        }

        return $this->email;
    }
}