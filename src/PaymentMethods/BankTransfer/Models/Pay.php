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