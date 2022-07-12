<?php

declare(strict_types=1);

namespace Buckaroo\Models;

class Article extends Model
{
    protected string $identifier;
    protected string $type;
    protected string $brand;
    protected string $manufacturer;
    protected string $unitCode;
    protected float $price;
    protected int $quantity;
    protected float $vatPercentage;
    protected string $vatCategory;
    protected string $description;
}
