<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'firstName'    => 'ConsumerFirstName',
        'lastName'   => 'ConsumerLastName'
    ];
}