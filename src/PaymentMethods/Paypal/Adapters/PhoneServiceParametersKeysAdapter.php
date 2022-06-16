<?php

namespace Buckaroo\PaymentMethods\Paypal\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'mobile'                => 'Phone'
    ];
}