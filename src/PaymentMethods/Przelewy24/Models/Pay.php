<?php

namespace Buckaroo\PaymentMethods\Przelewy24\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Przelewy24\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\Przelewy24\Service\ParameterKeys\EmailAdapter;

class Pay extends ServiceParameter
{
    protected CustomerAdapter $customer;
    protected EmailAdapter $email;

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
            return $this->customer(new CustomerAdapter(new Person($customer)));
        }

        if($customer instanceof CustomerAdapter)
        {
            $this->customer = $customer;
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new EmailAdapter(new Email($email)));
        }

        if($email instanceof EmailAdapter)
        {
            $this->email = $email;
        }

        return $this->email;
    }
}