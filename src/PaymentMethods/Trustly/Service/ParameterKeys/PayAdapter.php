<?php

namespace Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PayAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'country'       => 'CustomerCountryCode'
    ];
}