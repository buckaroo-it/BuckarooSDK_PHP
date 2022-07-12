<?php

namespace Buckaroo\PaymentMethods\In3\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'        => 'Code',
        'description'       => 'Name',
        'quantity'          => 'Quantity',
        'price'             => 'Price'
    ];
}