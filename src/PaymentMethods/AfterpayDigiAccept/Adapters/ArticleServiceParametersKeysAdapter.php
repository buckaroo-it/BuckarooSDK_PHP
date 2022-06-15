<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;

class ArticleServiceParametersKeysAdapter extends ServiceParametersKeysAdapter
{
    protected array $keys = [
        'identifier'        => 'ArticleId',
        'quantity'          => 'ArticleQuantity',
        'price'             => 'ArticleUnitprice',
        'vatCategory'       => 'ArticleVatcategory',
        'description'       => 'ArticleDescription'
    ];
}