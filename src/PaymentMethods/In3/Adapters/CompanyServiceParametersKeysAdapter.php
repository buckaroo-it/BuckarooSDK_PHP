<?php

namespace Buckaroo\PaymentMethods\In3\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class CompanyServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'companyName'        => 'Name'
    ];
}