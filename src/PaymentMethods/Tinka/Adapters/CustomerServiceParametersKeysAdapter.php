<?php

namespace Buckaroo\PaymentMethods\Tinka\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'lastNamePrefix'    => 'PrefixLastName',
        'birthDate'         => 'DateOfBirth'
    ];
}