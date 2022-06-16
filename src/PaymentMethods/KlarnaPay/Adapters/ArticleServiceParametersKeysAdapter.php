<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'price'             => 'GrossUnitPrice'
    ];
}