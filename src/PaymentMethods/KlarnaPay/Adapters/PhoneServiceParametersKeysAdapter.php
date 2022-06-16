<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'             => 'Phone'
    ];
}