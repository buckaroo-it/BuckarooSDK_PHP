<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\BankAccount;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\BuckarooWallet\Adapters\BankAccountServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Adapters\EmailServiceParametersKeysAdapter;

class Wallet extends ServiceParameter
{
    protected string $walletId;
    protected string $status;
    protected string $walletMutationGuid;

    protected CustomerServiceParametersKeysAdapter $customer;
    protected EmailServiceParametersKeysAdapter $email;
    protected BankAccountServiceParametersKeysAdapter $bankAccount;

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

    public function bankAccount($bankAccount = null)
    {
        if(is_array($bankAccount))
        {
            return $this->bankAccount(new BankAccountServiceParametersKeysAdapter(new BankAccount($bankAccount)));
        }

        if($bankAccount instanceof BankAccountServiceParametersKeysAdapter)
        {
            $this->bankAccount = $bankAccount;
        }

        return $this->bankAccount;
    }
}