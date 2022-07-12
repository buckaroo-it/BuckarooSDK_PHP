<?php

namespace Buckaroo\PaymentMethods\Billink\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'houseNumber'               => 'StreetNumber',
        'houseNumberAdditional'     => 'StreetNumberAdditional',
        'zipcode'                   => 'PostalCode'
    ];
}