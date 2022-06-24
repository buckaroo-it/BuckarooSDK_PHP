<?php

namespace Buckaroo\PaymentMethods\SEPA\Models;

use Buckaroo\PaymentMethods\Paypal\Models\Address;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\AddressAdapter;

class ExtraInfo extends Pay
{
    protected AddressAdapter $address;

    protected string $customerReferencePartyName;
    protected string $customerReferencePartyCode;
    protected string $customercode;
    protected string $contractID;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['address', 'customer']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            return $this->address(new AddressAdapter(new Address($address)));
        }

        if($address instanceof AddressAdapter)
        {
            $this->address = $address;
        }

        return $this->address;
    }
}