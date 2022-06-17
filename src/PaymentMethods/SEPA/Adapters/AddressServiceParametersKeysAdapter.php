<?php

namespace Buckaroo\PaymentMethods\SEPA\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'houseNumberAdditional'        => 'housenumbersuffix'
    ];
}