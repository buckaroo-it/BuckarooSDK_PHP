<?php

namespace Buckaroo\PaymentMethods\SEPA\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'name'        => 'customeraccountname'
    ];
}