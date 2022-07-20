<?php

namespace Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'lastNamePrefix'    => 'PrefixLastName',
        'birthDate'         => 'DateOfBirth'
    ];
}