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

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new CustomerAdapter(new Person($customer));
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new EmailAdapter(new Email($email));
        }

        return $this->email;
    }
}