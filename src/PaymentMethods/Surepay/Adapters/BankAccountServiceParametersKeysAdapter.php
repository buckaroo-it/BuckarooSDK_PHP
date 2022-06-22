<?php

namespace Buckaroo\PaymentMethods\Surepay\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class BankAccountServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'accountName'   => 'customeraccountname'
    ];
}