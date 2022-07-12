<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'             => 'Phone'
    ];
}