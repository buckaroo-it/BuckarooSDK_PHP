<?php

namespace Buckaroo\PaymentMethods\In3\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'        => 'Phone'
    ];
}