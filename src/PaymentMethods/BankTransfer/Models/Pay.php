<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\BankTransfer\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\BankTransfer\Adapters\EmailServiceParametersKeysAdapter;

class Pay extends ServiceParameter
{
    protected CustomerServiceParametersKeysAdapter $customer;
    protected EmailServiceParametersKeysAdapter $email;

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