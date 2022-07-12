<?php

namespace Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'name'        => 'customeraccountname'
    ];
}