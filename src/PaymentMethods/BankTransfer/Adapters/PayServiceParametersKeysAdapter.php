<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PayServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'country' => 'CustomerCountry'
    ];
}