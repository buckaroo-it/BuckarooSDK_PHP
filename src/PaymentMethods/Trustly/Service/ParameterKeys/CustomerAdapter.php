<?php

namespace Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'         => 'CustomerFirstName',
        'lastName'          => 'CustomerLastName',
    ];
}