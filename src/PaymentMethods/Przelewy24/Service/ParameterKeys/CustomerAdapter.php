<?php

namespace Buckaroo\PaymentMethods\Przelewy24\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'        => 'CustomerFirstName',
        'lastName'        => 'CustomerLastName',
        'email'        => 'CustomerEmail',
    ];
}