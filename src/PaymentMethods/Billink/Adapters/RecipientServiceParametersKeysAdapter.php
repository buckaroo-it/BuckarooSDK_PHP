<?php

namespace Buckaroo\PaymentMethods\Billink\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;

class RecipientServiceParametersKeysAdapter extends ServiceParametersKeysAdapter implements RecipientInterface
{
    protected array $keys = [
        'title'                     => 'Salutation',
    ];
}