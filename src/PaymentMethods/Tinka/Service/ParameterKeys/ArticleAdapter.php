<?php

namespace Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'price'        => 'UnitGrossPrice'
    ];
}