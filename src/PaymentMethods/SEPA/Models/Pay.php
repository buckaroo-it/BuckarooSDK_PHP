<?php

namespace Buckaroo\PaymentMethods\SEPA\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\CustomerAdapter;

class Pay extends ServiceParameter
{
    protected CustomerAdapter $customer;

    protected string $bic;
    protected string $iban;
    protected string $collectdate;
    protected string $mandateReference;
    protected string $mandateDate;
    protected string $startRecurrent;

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new CustomerAdapter(new Person($customer));
        }

        return $this->customer;
    }
}