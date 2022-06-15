<?php

namespace Buckaroo\PaymentMethods\Afterpay\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;

class PersonServiceParametersKeysAdapter extends ServiceParametersKeysAdapter implements RecipientInterface
{
    protected array $keys = [
        'title'        => 'Salutation'
    ];
}