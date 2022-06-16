<?php

namespace Buckaroo\PaymentMethods\Billink\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'landLine'      => 'Phone',
        'mobile'        => 'MobilePhone'
    ];
}