<?php

namespace Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;

class RecipientAdapter extends ServiceParametersKeysAdapter implements RecipientInterface
{
    protected array $keys = [
        'title'                     => 'Salutation',
        'chamberOfCommerce'        => 'IdentificationNumber'
    ];
}