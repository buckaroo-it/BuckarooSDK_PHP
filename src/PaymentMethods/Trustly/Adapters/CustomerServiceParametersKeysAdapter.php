<?php

namespace Buckaroo\PaymentMethods\Trustly\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'        => 'CustomerFirstName',
        'lastName'          => 'CustomerLastName',
        'country'           => 'CustomerCountryCode'
    ];
}