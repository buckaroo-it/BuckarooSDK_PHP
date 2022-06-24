<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'gender'            => 'customergender',
        'firstName'         => 'CustomerFirstName',
         'lastName'         => 'CustomerLastName'
    ];
}