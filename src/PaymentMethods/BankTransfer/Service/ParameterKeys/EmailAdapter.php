<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class EmailAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'email' => 'Customeremail'
    ];
}