<?php

namespace Buckaroo\PaymentMethods\RequestToPay\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CustomerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'name'        => 'DebtorName'
    ];
}