<?php

namespace Buckaroo\PaymentMethods\CreditClick\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;

class Pay extends ServiceParameter
{
    protected Person $customer;
    protected Email $email;

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer =  new Person($customer);
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new Email($email);
        }

        return $this->email;
    }
}