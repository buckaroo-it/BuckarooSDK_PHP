<?php

namespace Buckaroo\PaymentMethods\SEPA\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\SEPA\Adapters\CustomerServiceParametersKeysAdapter;

class Pay extends ServiceParameter
{
    protected CustomerServiceParametersKeysAdapter $customer;

    protected string $bic;
    protected string $iban;
    protected string $collectdate;
    protected string $mandateReference;
    protected string $mandateDate;
    protected string $startRecurrent;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['customer']))
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
}