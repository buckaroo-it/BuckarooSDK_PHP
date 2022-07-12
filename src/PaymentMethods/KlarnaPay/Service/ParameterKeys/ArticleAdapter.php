<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'price'             => 'GrossUnitPrice'
    ];
}