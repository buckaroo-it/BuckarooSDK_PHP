<?php

namespace Buckaroo\PaymentMethods\In3\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'        => 'Phone'
    ];
}