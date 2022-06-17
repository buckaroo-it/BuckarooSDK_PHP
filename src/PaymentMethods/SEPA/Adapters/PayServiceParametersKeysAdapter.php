<?php

namespace Buckaroo\PaymentMethods\SEPA\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class PayServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'bic'       => 'customerbic',
        'iban'        => 'CustomerIBAN'
    ];
}