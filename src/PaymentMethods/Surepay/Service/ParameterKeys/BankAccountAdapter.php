<?php

namespace Buckaroo\PaymentMethods\Surepay\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class BankAccountAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'accountName'   => 'customeraccountname'
    ];
}