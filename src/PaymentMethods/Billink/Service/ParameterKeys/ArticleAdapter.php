<?php

namespace Buckaroo\PaymentMethods\Billink\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'    => 'Identifier',
        'price'         => 'GrossUnitPriceIncl',
        'priceExcl'     => 'GrossUnitPriceExcl'
    ];
}