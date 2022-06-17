<?php

namespace Buckaroo\PaymentMethods\Tinka\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'houseNumber'           => 'StreetNumber',
        'houseNumberAdditional' => 'StreetNumberAdditional',
        'zipcode'               => 'PostalCode'
    ];
}