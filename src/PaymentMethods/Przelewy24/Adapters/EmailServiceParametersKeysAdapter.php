<?php

namespace Buckaroo\PaymentMethods\Przelewy24\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class EmailServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'email'        => 'CustomerEmail'
    ];
}