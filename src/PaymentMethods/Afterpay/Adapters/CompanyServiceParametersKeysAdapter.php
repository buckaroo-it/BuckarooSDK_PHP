<?php

namespace Buckaroo\PaymentMethods\Afterpay\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;

class CompanyServiceParametersKeysAdapter extends ServiceParametersKeysAdapter implements RecipientInterface
{
    protected array $keys = [
        'chamberOfCommerce'        => 'IdentificationNumber'
    ];
}