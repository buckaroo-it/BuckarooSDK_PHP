<?php

namespace Buckaroo\PaymentMethods\Trustly\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys\CustomerAdapter;

class Pay extends ServiceParameter
{
    protected CustomerAdapter $customer;

    protected string $country;

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new CustomerAdapter(new Person($customer));
        }

        return $this->customer;
    }
}