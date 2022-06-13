<?php

namespace Buckaroo\PaymentMethods\RequestToPay\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'name'        => 'DebtorName'
    ];
}