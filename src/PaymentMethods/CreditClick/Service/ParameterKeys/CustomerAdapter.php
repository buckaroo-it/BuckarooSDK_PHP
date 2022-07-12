<?php

namespace Buckaroo\PaymentMethods\CreditClick\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'        => 'firstname',
        'lastName'        => 'lastname',
        'email'        => 'email',
    ];
}