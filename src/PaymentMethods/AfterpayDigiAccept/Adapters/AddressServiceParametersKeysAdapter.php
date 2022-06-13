<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Address;

class AddressServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected $prefix = '';

    protected array $keys = [
        'phone'        => 'PhoneNumber'
    ];

    public function __construct(Address $address, $prefix = '')
    {
        $this->prefix = $prefix;

        return parent::__construct($address);
    }

    public function serviceParameterKeyOf($propertyName): string
    {
        if(isset($this->keys[$propertyName])) {
            return $this->prefix . $this->keys[$propertyName];
        }

        return $this->prefix . ucfirst($propertyName);
    }
}