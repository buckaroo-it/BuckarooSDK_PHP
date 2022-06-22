<?php

namespace Buckaroo\PaymentMethods\iDin\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class IssuerServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'issuer'    => 'issuerId'
    ];
}