<?php

declare(strict_types=1);

namespace Buckaroo\Models;

class Article extends Model
{
    protected
        $identifier,
        $type,
        $articleId,
        $articleNumber,
        $articleDescription,
        $articleUnitprice,
        $articleQuantity,
        $articleVatcategory,
        $brand,
        $manufacturer,
        $color,
        $size,
        $unitCode,
        $description,
        $vatPercentage,
        $quantity,
        $price,
        $grossUnitPrice,
        $grossUnitPriceIncl,
        $grossUnitPriceExcl,
        $reservationNumber;
}
