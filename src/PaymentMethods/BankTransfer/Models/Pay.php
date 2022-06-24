<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys\EmailAdapter;

class Pay extends ServiceParameter
{
    protected CustomerAdapter $customer;
    protected EmailAdapter $email;

    protected bool $sendMail;
    protected string $dateDue;
    protected string $country;

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