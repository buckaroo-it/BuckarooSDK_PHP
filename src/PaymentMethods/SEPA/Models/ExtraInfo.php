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

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }
}