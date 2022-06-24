<?php

namespace Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PayAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'bic'       => 'customerbic',
        'iban'        => 'CustomerIBAN'
    ];
}