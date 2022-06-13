<?php

namespace Buckaroo\PaymentMethods\Przelewy24\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'        => 'CustomerFirstName',
        'lastName'        => 'CustomerLastName',
        'email'        => 'CustomerEmail',
    ];
}