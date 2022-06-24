<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class EmailAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'email'             => 'CustomerEmail'
    ];
}