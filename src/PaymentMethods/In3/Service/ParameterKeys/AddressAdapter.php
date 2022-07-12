<?php

namespace Buckaroo\PaymentMethods\In3\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'houseNumberAdditional'     => 'HouseNumberSuffix',
        'zipcode'                   => 'ZipCode'
    ];
}