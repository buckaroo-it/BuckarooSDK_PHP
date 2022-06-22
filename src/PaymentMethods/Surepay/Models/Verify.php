<?php

namespace Buckaroo\PaymentMethods\Surepay\Models;

use Buckaroo\Models\BankAccount;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Surepay\Adapters\BankAccountServiceParametersKeysAdapter;

class Verify extends ServiceParameter
{
    protected BankAccountServiceParametersKeysAdapter $bankAccount;

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
            return $this->bankAccount(new BankAccountServiceParametersKeysAdapter(new BankAccount($bankAccount)));
        }

        if($bankAccount instanceof BankAccountServiceParametersKeysAdapter)
        {
            $this->bankAccount = $bankAccount;
        }

        return $this->bankAccount;
    }
}