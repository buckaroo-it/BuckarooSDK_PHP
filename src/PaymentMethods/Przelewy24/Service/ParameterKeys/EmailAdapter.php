<?php

namespace Buckaroo\PaymentMethods\Przelewy24\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class EmailAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'email'        => 'CustomerEmail'
    ];
}