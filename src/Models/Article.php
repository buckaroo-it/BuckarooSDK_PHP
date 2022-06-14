<?php

declare(strict_types=1);

namespace Buckaroo\Models;

class Article extends Model
{
    protected string $identifier;
    protected string $type;
    protected string $articleNumber;
    protected string $brand;
    protected string $manufacturer;
    protected string $color;
    protected string $size;
    protected string $unitCode;
    protected float $unitPrice;
    protected string $description;
    protected string $vatPercentage;
    protected string $vatCategory;
    protected int $quantity;
    protected float $price;
    protected float $grossUnitPrice;
    protected float $grossUnitPriceIncl;
    protected float $grossUnitPriceExcl;
    protected string $reservationNumber;
}
