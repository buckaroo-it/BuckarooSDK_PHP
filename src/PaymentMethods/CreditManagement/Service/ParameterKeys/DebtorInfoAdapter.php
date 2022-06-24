<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class DebtorInfoAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'code' => 'DebtorCode'
    ];
}