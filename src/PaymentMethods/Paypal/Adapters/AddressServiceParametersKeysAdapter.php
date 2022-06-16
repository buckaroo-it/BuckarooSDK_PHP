<?php

namespace Buckaroo\PaymentMethods\Paypal\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'street'                => 'Street1',
        'city'                  => 'CityName',
        'state'                 => 'StateOrProvince',
        'zipcode'               => 'PostalCode'
    ];
}