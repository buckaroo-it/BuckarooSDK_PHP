<?php

namespace Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'    => 'Phone'
    ];
}