<?php

namespace Buckaroo\PaymentMethods\In3\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'        => 'Code',
        'description'       => 'Name',
        'quantity'          => 'Quantity',
        'price'             => 'Price'
    ];
}