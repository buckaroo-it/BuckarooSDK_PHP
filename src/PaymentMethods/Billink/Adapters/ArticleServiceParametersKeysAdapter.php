<?php

namespace Buckaroo\PaymentMethods\Billink\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'    => 'Identifier',
        'price'         => 'GrossUnitPriceIncl',
        'priceExcl'     => 'GrossUnitPriceExcl'
    ];
}