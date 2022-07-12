<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'    => 'ConsumerFirstName',
        'lastName'   => 'ConsumerLastName'
    ];
}