<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

class In3ArticleAdapter extends Adapter
{
    protected array $keys = [
        'identifier'        => 'Code',
        'description'       => 'Name',
        'quantity'          => 'Quantity',
        'price'             => 'Price'
    ];
}