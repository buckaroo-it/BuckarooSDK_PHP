<?php

namespace Buckaroo\PaymentMethods\Billink\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PhoneAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'landLine'      => 'Phone',
        'mobile'        => 'MobilePhone'
    ];
}