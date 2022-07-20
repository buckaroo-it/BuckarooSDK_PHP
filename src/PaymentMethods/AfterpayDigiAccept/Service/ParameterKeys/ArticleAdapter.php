<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'        => 'ArticleId',
        'quantity'          => 'ArticleQuantity',
        'price'             => 'ArticleUnitprice',
        'vatCategory'       => 'ArticleVatcategory',
        'description'       => 'ArticleDescription'
    ];
}