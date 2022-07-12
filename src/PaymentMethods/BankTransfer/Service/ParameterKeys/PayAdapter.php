<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PayAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'country' => 'CustomerCountry'
    ];
}