<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\BankAccount;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\BankAccountAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\EmailAdapter;

class Wallet extends ServiceParameter
{
    protected string $walletId;
    protected string $status;
    protected string $walletMutationGuid;

    protected CustomerAdapter $customer;
    protected EmailAdapter $email;
    protected BankAccountAdapter $bankAccount;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['customer', 'email', 'bankAccount']))
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

    public function bankAccount($bankAccount = null)
    {
        if(is_array($bankAccount))
        {
            return $this->bankAccount(new BankAccountAdapter(new BankAccount($bankAccount)));
        }

        if($bankAccount instanceof BankAccountAdapter)
        {
            $this->bankAccount = $bankAccount;
        }

        return $this->bankAccount;
    }
}