<?php

namespace Buckaroo\PaymentMethods\Paypal\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'street'                => 'Street1',
        'city'                  => 'CityName',
        'state'                 => 'StateOrProvince',
        'zipcode'               => 'PostalCode'
    ];
}