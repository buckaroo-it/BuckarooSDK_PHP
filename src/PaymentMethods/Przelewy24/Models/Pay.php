<?php

namespace Buckaroo\PaymentMethods\Przelewy24\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Przelewy24\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\Przelewy24\Adapters\EmailServiceParametersKeysAdapter;

class Pay extends ServiceParameter
{
    protected CustomerServiceParametersKeysAdapter $customer;
    protected EmailServiceParametersKeysAdapter $email;

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
            return $this->customer(new CustomerServiceParametersKeysAdapter(new Person($customer)));
        }

        if($customer instanceof CustomerServiceParametersKeysAdapter)
        {
            $this->customer = $customer;
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new EmailServiceParametersKeysAdapter(new Email($email)));
        }

        if($email instanceof EmailServiceParametersKeysAdapter)
        {
            $this->email = $email;
        }

        return $this->email;
    }
}