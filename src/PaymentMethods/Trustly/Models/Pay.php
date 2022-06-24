<?php

namespace Buckaroo\PaymentMethods\Trustly\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys\CustomerAdapter;

class Pay extends ServiceParameter
{
    protected CustomerAdapter $customer;

    protected string $country;

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
            return $this->customer(new CustomerAdapter(new Person($customer)));
        }

        if($customer instanceof CustomerAdapter)
        {
            $this->customer = $customer;
        }

        return $this->customer;
    }
}