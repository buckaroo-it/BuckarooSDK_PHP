<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'    => 'ProductId',
        'description'   => 'ProductName',
        'price'         => 'PricePerUnit'
    ];
}