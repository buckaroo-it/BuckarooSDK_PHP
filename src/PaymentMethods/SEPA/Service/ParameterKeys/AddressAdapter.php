<?php

namespace Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class AddressAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'houseNumberAdditional'        => 'housenumbersuffix'
    ];
}