<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class EmailServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'email'             => 'CustomerEmail'
    ];
}