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
    protected array $propertiesAsMethods = ['customer', 'email', 'bankAccount'];

    protected string $walletId;
    protected string $status;
    protected string $walletMutationGuid;

    protected CustomerAdapter $customer;
    protected EmailAdapter $email;
    protected BankAccountAdapter $bankAccount;

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

    public function bankAccount($bankAccount = null)
    {
        if(is_array($bankAccount))
        {
            $this->bankAccount = new BankAccountAdapter(new BankAccount($bankAccount));
        }

        return $this->bankAccount;
    }
}