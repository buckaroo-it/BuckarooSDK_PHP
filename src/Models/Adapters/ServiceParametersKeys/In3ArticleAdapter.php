<?php

namespace Buckaroo\Models\Adapters\ServiceParametersKeys;

class In3ArticleAdapter extends Adapter
{
    protected array $keys = [
        'identifier'        => 'Code',
        'description'       => 'Name',
        'quantity'          => 'Quantity',
        'price'             => 'Price'
    ];
}