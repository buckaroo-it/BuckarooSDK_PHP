<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'gender'            => 'customergender',
        'firstName'         => 'CustomerFirstName',
         'lastName'         => 'CustomerLastName'
    ];
}