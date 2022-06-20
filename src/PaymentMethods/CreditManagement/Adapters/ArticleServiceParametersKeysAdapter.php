<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'    => 'ProductId',
        'description'   => 'ProductName',
        'price'         => 'PricePerUnit'
    ];
}