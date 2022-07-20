<?php

namespace Buckaroo\PaymentMethods\Tinka\Models;

use Buckaroo\Models\{Address, Email, Person, Phone, ServiceParameter};
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\{CustomerAdapter};
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\PhoneAdapter;

class Recipient extends ServiceParameter
{
    private string $type;

    protected CustomerAdapter $recipient;

    protected AddressAdapter $address;
    protected PhoneAdapter $phone;
    protected Email $email;

    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    public function recipient($recipient = null)
    {
        if(is_array($recipient))
        {
            $this->recipient = new CustomerAdapter(new Person($recipient));
        }

        return $this->recipient;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            $this->phone = new PhoneAdapter(new Phone($phone));
        }

        return $this->phone;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email =  new Email($email);
        }

        return $this->email;
    }

    public function getGroupType(string $key): ?string
    {
        return $this->type . 'Customer';
    }
}