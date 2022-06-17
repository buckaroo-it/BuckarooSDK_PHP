<?php

namespace Buckaroo\PaymentMethods\BankTransfer\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class EmailServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'email' => 'Customeremail'
    ];
}