<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys;

class AddressAdapter extends ServiceAdapter
{
    protected array $keys = [
        'houseNumberAdditional'     => 'HouseNumberSuffix',
        'zipcode'                   => 'PostalCode'
    ];

    public function serviceParameterKeyOf($propertyName): string
    {
        if($this->prefix == 'Shipping' && $propertyName == 'country')
        {
            return 'ShippingCountryCode';
        }

        $name = (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);

        return $this->prefix . $name;
    }
}