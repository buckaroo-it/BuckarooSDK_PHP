<?php

namespace Buckaroo\PaymentMethods\Paypal\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys\{AddressAdapter};
use Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys\PhoneAdapter;

class ExtraInfo extends ServiceParameter
{
    protected AddressAdapter $address;
    protected PhoneAdapter $phone;
    protected Person $customer;

    protected string $noShipping;
    protected bool $addressOverride;

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new Person($customer);
        }

        return $this->customer;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            $this->phone = new PhoneAdapter(new Phone($phone));
        }

        return $this->phone;
    }
}