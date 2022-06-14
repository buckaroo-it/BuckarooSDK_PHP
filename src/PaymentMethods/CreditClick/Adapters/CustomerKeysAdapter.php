<?php

namespace Buckaroo\PaymentMethods\CreditClick\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'        => 'firstname',
        'lastName'        => 'lastname',
        'email'        => 'email',
    ];
}