<?php

namespace Buckaroo\PaymentMethods\Surepay\Models;

use Buckaroo\Models\BankAccount;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Surepay\Service\ParameterKeys\BankAccountAdapter;

class Verify extends ServiceParameter
{
    protected BankAccountAdapter $bankAccount;

    public function bankAccount($bankAccount = null)
    {
        if(is_array($bankAccount))
        {
            $this->bankAccount =  new BankAccountAdapter(new BankAccount($bankAccount));
        }

        return $this->bankAccount;
    }
}