<?php

namespace Buckaroo\PaymentMethods\In3\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'houseNumberAdditional'     => 'HouseNumberSuffix',
        'zipcode'                   => 'ZipCode'
    ];
}