<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class DebtorInfoServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'code' => 'DebtorCode'
    ];
}