<?php

namespace Buckaroo\PaymentMethods\iDin\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class IssuerAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'issuer'    => 'issuerId'
    ];
}