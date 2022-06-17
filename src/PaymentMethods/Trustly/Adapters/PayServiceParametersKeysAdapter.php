<?php

namespace Buckaroo\PaymentMethods\Trustly\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PayServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'country'       => 'CustomerCountryCode'
    ];
}