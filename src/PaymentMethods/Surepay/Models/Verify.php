<?php

namespace Buckaroo\PaymentMethods\Surepay\Models;

use Buckaroo\Models\BankAccount;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Surepay\Service\ParameterKeys\BankAccountAdapter;

class Verify extends ServiceParameter
{
    protected BankAccountAdapter $bankAccount;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['bankAccount']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
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