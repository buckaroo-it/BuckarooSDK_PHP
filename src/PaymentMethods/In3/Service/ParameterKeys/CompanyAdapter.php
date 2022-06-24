<?php

namespace Buckaroo\PaymentMethods\In3\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CompanyAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'companyName'        => 'Name'
    ];
}